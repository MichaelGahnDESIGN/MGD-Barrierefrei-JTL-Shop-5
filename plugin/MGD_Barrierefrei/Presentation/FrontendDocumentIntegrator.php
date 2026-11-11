<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

/** Gibt nur geprüfte Werte als JSON aus. Das Widget baut die Oberfläche selbst (Shadow DOM). */
final class FrontendDocumentIntegrator
{
    /** @param array<mixed> $arguments @param array<string,string> $settings */
    public function integrate(array $arguments, string $frontendUrl, array $settings): void
    {
        $document = $arguments['document'] ?? null;
        if (!is_object($document) || !$this->safeFrontendUrl($frontendUrl)) {
            return;
        }
        $json = json_encode(
            self::config($settings),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($json === false) {
            return;
        }
        $src = rtrim($frontendUrl, '/') . '/js/mgd-barrierefrei.js';
        // config + widget ans Ende vom body
        $this->append(
            $document,
            'body',
            '<script type="application/json" id="mgd-barrierefrei-config">' . $json . '</script>'
            . '<script src="' . $this->escape($src) . '" defer data-mgd-barrierefrei></script>'
        );
    }

    /** @param array<string,string> $s @return array<string,mixed> */
    public static function config(array $s): array
    {
        $pick = static fn (string $v, array $ok, string $def): string => in_array($v, $ok, true) ? $v : $def;
        $de = self::text($s['label'] ?? '');
        $en = self::text($s['labelEn'] ?? '');

        return [
            'position' => $pick($s['position'] ?? '', ['left', 'right'], 'left'),
            'accent' => preg_match('/^#[a-fA-F0-9]{6}$/D', $s['accent'] ?? '') === 1 ? $s['accent'] : '#b91c1c',
            'lang' => $pick($s['lang'] ?? '', ['auto', 'de', 'en'], 'auto'),
            'theme' => $pick($s['theme'] ?? '', ['auto', 'light', 'dark'], 'auto'),
            // Standardtext leer lassen > Widget nimmt seine Übersetzung
            'labels' => ['de' => $de === 'Barrierefreiheit' ? '' : $de, 'en' => $en === 'Accessibility' ? '' : $en],
            'statementUrl' => self::safeStatementUrl($s['statementUrl'] ?? ''),
            'offset' => max(0, min(200, (int) ($s['offset'] ?? 0))),
        ];
    }

    private function append(object $document, string $selector, string $markup): void
    {
        $result = is_callable([$document, 'find']) ? $document->find($selector) : null;
        if (is_object($result) && is_callable([$result, 'append'])) {
            $result->append($markup);
        }
    }

    private function safeFrontendUrl(string $url): bool
    {
        $parts = parse_url($url);
        return is_array($parts) && in_array($parts['scheme'] ?? '', ['https', 'http'], true) && is_string($parts['host'] ?? null) && ($parts['host'] ?? '') !== '';
    }

    private static function safeStatementUrl(string $url): string
    {
        $url = trim($url);
        if (preg_match('#^/(?!/)[A-Za-z0-9._~:/?&=%+-]*$#D', $url) === 1) {
            return $url;
        }
        $parts = parse_url($url);
        return is_array($parts) && ($parts['scheme'] ?? '') === 'https' && is_string($parts['host'] ?? null) && !preg_match('/[\s"\'<>]/', $url) ? $url : '';
    }

    private static function text(string $value): string
    {
        return mb_substr(trim(strip_tags($value)), 0, 80);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
