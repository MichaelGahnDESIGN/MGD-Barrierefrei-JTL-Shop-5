<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Catalog;

/** Macht aus dem Feed eine saubere Liste; alles was nicht passt fliegt raus. */
final class FeedNormalizer
{
    // Allowlist Host - nur die eigene Plattform
    public const HOST = 'theme.michael-gahn.de';
    public const DEFAULT_DETAILS_URL = 'https://theme.michael-gahn.de/plugins';

    private const GITHUB_HOST = 'github.com';
    private const GITHUB_OWNER = 'MichaelGahnDESIGN';
    private const MAX_ITEMS = 50;
    private const MAX_RELATED = 8;
    private const ID_PATTERN = '/^[a-z0-9][a-z0-9-]{0,79}$/D';

    /**
     * @param list<string> $platforms  Plattform-Werte dieses Shops, z. B. ['jtl', 'jtl5']
     * @param list<string> $crossPlatform  plattformübergreifende Werte, werden immer gezeigt
     */
    public function __construct(
        private readonly array $platforms,
        private readonly string $ownId,
        private readonly array $crossPlatform = ['other', 'all'],
    ) {
    }

    /**
     * @param mixed $feed  decodiertes JSON
     * @return list<array<string, mixed>>|null  null = Feed unbrauchbar
     */
    public function normalize(mixed $feed): ?array
    {
        if (!is_array($feed) || ($feed['schemaVersion'] ?? null) !== 1 || !is_array($feed['items'] ?? null)) {
            return null;
        }

        $accepted = array_merge($this->platforms, $this->crossPlatform);
        $recommended = [];
        $items = [];
        $seen = [];
        foreach ($feed['items'] as $entry) {
            if (!is_array($entry) || !is_string($entry['id'] ?? null) || preg_match(self::ID_PATTERN, $entry['id']) !== 1) {
                continue;
            }
            $id = $entry['id'];
            if ($id === $this->ownId) {
                // eigenes Plugin nicht zeigen, aber seine related-Liste merken
                $recommended = $this->relatedIds($entry['related'] ?? null);
                continue;
            }
            if (isset($seen[$id]) || !in_array($entry['platform'] ?? null, $accepted, true)) {
                continue;
            }
            $item = $this->item($id, $entry);
            if ($item === null) {
                continue;
            }
            $seen[$id] = true;
            $items[] = $item;
            if (count($items) >= self::MAX_ITEMS) {
                break;
            }
        }

        // related bevorzugen, danach live vor announced (usort ist stabil)
        foreach ($items as $i => $item) {
            $items[$i]['recommended'] = in_array($item['id'], $recommended, true);
        }
        usort($items, static fn (array $a, array $b): int => [(int) !$a['recommended'], (int) ($a['status'] !== 'live')]
            <=> [(int) !$b['recommended'], (int) ($b['status'] !== 'live')]);

        return $items;
    }

    /** @param array<string, mixed> $entry */
    private function item(string $id, array $entry): ?array
    {
        $name = $this->text($entry['name'] ?? null, 80);
        $status = $entry['status'] ?? null;
        if ($name === '' || !in_array($status, ['live', 'announced'], true)) {
            return null;
        }
        $tier = ($entry['tier'] ?? null) === 'free' ? 'free' : 'paid';
        $purchase = is_array($entry['purchase'] ?? null) ? $entry['purchase']['url'] ?? null : null;
        $imageUrl = $this->mediaUrl($entry['imageUrl'] ?? null);
        $alt = $imageUrl === null ? '' : $this->text($entry['imageAlt'] ?? null, 160);
        $accent = is_string($entry['accent'] ?? null) && preg_match('/^#[0-9a-fA-F]{6}$/D', $entry['accent']) === 1
            ? strtolower($entry['accent']) : null;

        return [
            'id' => $id,
            'name' => $name,
            'description' => $this->text($entry['description'] ?? null, 500),
            'tier' => $tier,
            'status' => $status,
            'version' => $this->version($entry['version'] ?? null),
            'detailsUrl' => $this->url($entry['detailsUrl'] ?? null, self::HOST, '#^/[A-Za-z0-9._~/-]*$#D')
                ?? $this->url($purchase, self::HOST, '#^/[A-Za-z0-9._~/-]*$#D')
                ?? self::DEFAULT_DETAILS_URL,
            // Download nur live + kostenlos, Rest läuft über die Detailseite
            'downloadUrl' => $status === 'live' && $tier === 'free'
                ? $this->url($entry['downloadUrl'] ?? null, self::HOST, '#^/download/[a-z0-9-]{1,80}$#D') : null,
            'repositoryUrl' => $this->url($entry['repositoryUrl'] ?? null, self::GITHUB_HOST, '#^/' . self::GITHUB_OWNER . '/[A-Za-z0-9._-]{1,100}/?$#D'),
            'iconUrl' => $this->mediaUrl($entry['iconUrl'] ?? null),
            'imageUrl' => $imageUrl,
            'imageAlt' => $alt,
            'accent' => $accent,
            'recommended' => false,
        ];
    }

    /** @return list<string> */
    private function relatedIds(mixed $related): array
    {
        if (!is_array($related)) {
            return [];
        }
        $ids = [];
        foreach (array_slice($related, 0, self::MAX_RELATED) as $id) {
            if (is_string($id) && preg_match(self::ID_PATTERN, $id) === 1) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    // Nur Text, kein HTML; Ausgabe wird später trotzdem escaped
    private function text(mixed $value, int $max): string
    {
        if (!is_string($value)) {
            return '';
        }
        $clean = preg_replace('/[\p{Cc}\p{Cf}\s]+/u', ' ', strip_tags($value));

        return $clean === null ? '' : mb_substr(trim($clean), 0, $max);
    }

    // "v1.0.1" > "1.0.1", sonst null
    private function version(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $version = ltrim(trim($value), 'vV');

        return preg_match('/^\d{1,4}\.\d{1,4}\.\d{1,4}(?:[-+][0-9A-Za-z.-]{1,30})?$/D', $version) === 1 ? $version : null;
    }

    private function mediaUrl(mixed $value): ?string
    {
        return $this->url($value, self::HOST, '#^/uploads/catalog/[a-f0-9]{32}\.webp$#D');
    }

    // nur https, genau dieser Host, kein user/port, Pfad muss zum Muster passen
    private function url(mixed $value, string $host, string $pathPattern): ?string
    {
        if (!is_string($value) || $value === '' || strlen($value) > 300 || preg_match('/[\x00-\x20\x7F"\'<>\\\\]/', $value) === 1) {
            return null;
        }
        $parts = parse_url($value);
        if ($parts === false || strtolower($parts['scheme'] ?? '') !== 'https' || strtolower($parts['host'] ?? '') !== $host
            || isset($parts['user']) || isset($parts['pass']) || isset($parts['port']) || isset($parts['query']) || isset($parts['fragment'])) {
            return null;
        }

        return preg_match($pathPattern, $parts['path'] ?? '/') === 1 ? $value : null;
    }
}
