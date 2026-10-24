<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Catalog;

/** Holt den Plugin-Feed von theme.michael-gahn.de, cached ihn und liefert nur geprüfte Einträge. */
final class PluginCatalog
{
    public const FEED_URL = 'https://theme.michael-gahn.de/api/catalog/plugins.json';

    private const TTL = 21600;            // 6 Stunden
    private const FAIL_WAIT = 300;        // nach Fehler 5 Min Ruhe
    private const REFRESH_COOLDOWN = 60;  // Button "Aktualisieren" max 1x pro Minute
    private const KEEP = 604800;          // alte Daten 7 Tage als Notnagel
    private const TIMEOUT = 5;
    private const MAX_BYTES = 524288;

    private readonly \Closure $transport;
    private readonly \Closure $clock;

    /**
     * @param (\Closure(string): (array{status: int, body: string}|null))|null $transport  nur für Tests
     * @param (\Closure(): int)|null $clock  nur für Tests
     */
    public function __construct(
        private readonly CatalogCache $cache,
        private readonly FeedNormalizer $normalizer,
        ?\Closure $transport = null,
        ?\Closure $clock = null,
    ) {
        $this->transport = $transport ?? static fn (string $url): ?array => self::httpGet($url);
        $this->clock = $clock ?? static fn (): int => time();
    }

    /** @return array{items: list<array<string, mixed>>, fetchedAt: int, stale: bool}|null  null = nichts erreichbar */
    public function load(bool $refresh = false): ?array
    {
        $now = ($this->clock)();
        $record = $this->cache->get() ?? [];
        $items = is_array($record['items'] ?? null) ? $record['items'] : null;
        $fetchedAt = is_int($record['fetchedAt'] ?? null) ? $record['fetchedAt'] : 0;
        $retryAfter = is_int($record['retryAfter'] ?? null) ? $record['retryAfter'] : 0;

        $age = $now - $fetchedAt;
        if ($items !== null && ($refresh ? $age < self::REFRESH_COOLDOWN : $age < self::TTL)) {
            return ['items' => $items, 'fetchedAt' => $fetchedAt, 'stale' => false];
        }
        if (!$refresh && $now < $retryAfter) {
            return $items === null ? null : ['items' => $items, 'fetchedAt' => $fetchedAt, 'stale' => true];
        }

        $fresh = $this->fetch();
        if ($fresh === null) {
            // Fehler merken, alte Liste (falls da) bleibt als Notnagel
            $this->cache->set(['items' => $items, 'fetchedAt' => $fetchedAt, 'retryAfter' => $now + self::FAIL_WAIT], self::KEEP);

            return $items === null ? null : ['items' => $items, 'fetchedAt' => $fetchedAt, 'stale' => true];
        }
        $this->cache->set(['items' => $fresh, 'fetchedAt' => $now, 'retryAfter' => 0], self::KEEP);

        return ['items' => $fresh, 'fetchedAt' => $now, 'stale' => false];
    }

    /** @return list<array<string, mixed>>|null */
    private function fetch(): ?array
    {
        try {
            $response = ($this->transport)(self::FEED_URL);
            if ($response === null || $response['status'] !== 200 || strlen($response['body']) > self::MAX_BYTES) {
                return null;
            }
            $json = json_decode($response['body'], true, 16, JSON_THROW_ON_ERROR);
        } catch (\Throwable) {
            return null;
        }

        return $this->normalizer->normalize($json);
    }

    /** @return array{status: int, body: string}|null */
    public static function httpGet(string $url): ?array
    {
        // Host-Allowlist + nur https, auch wenn die URL mal geändert wird
        $parts = parse_url($url);
        if ($parts === false || strtolower($parts['scheme'] ?? '') !== 'https' || strtolower($parts['host'] ?? '') !== FeedNormalizer::HOST) {
            return null;
        }
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\nUser-Agent: MGD-Barrierefrei-JTL-Shop-5\r\n",
                'timeout' => self::TIMEOUT,
                'ignore_errors' => true,
                'follow_location' => 0,
                'max_redirects' => 0,
            ],
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
        ]);
        $handle = @fopen($url, 'rb', false, $context);
        if ($handle === false) {
            return null;
        }
        stream_set_timeout($handle, self::TIMEOUT);
        $meta = stream_get_meta_data($handle);
        $body = stream_get_contents($handle, self::MAX_BYTES + 1);
        fclose($handle);

        $status = 0;
        foreach ((array) ($meta['wrapper_data'] ?? []) as $line) {
            if (is_string($line) && preg_match('#^HTTP/\S+\s+(\d{3})#', $line, $match) === 1) {
                $status = (int) $match[1];
            }
        }

        return $body === false || $status === 0 ? null : ['status' => $status, 'body' => $body];
    }
}
