<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

/** Verarbeitet nur Normalformen, damit Konfiguration nie zu freiem HTML wird. */
final class FrontendDocumentIntegrator
{
    /** @param array<mixed> $arguments @param array{position:string,label:string,accent:string,statementUrl:string} $settings */
    public function integrate(array $arguments, string $frontendUrl, array $settings): void
    {
        $document = $arguments['document'] ?? null;
        if (!is_object($document) || !$this->safeFrontendUrl($frontendUrl)) {
            return;
        }
        $base = rtrim($frontendUrl, '/');
        $position = $settings['position'] === 'right' ? 'right' : 'left';
        $label = $this->text($settings['label'], 'Barrierefreiheit');
        $accent = preg_match('/^#[a-fA-F0-9]{6}$/D', $settings['accent']) === 1 ? $settings['accent'] : '#b91c1c';
        $statement = $this->safeStatementUrl($settings['statementUrl']);
        $markup = '<div class="mgd-a11y" data-mgd-a11y data-position="' . $position . '" style="--mgd-a11y-accent:' . $this->escape($accent) . '">'
            . '<button class="mgd-a11y__trigger" type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="mgd-a11y-dialog"><span aria-hidden="true">♿</span><span>' . $this->escape($label) . '</span></button>'
            . '<section class="mgd-a11y__dialog" id="mgd-a11y-dialog" role="dialog" aria-modal="true" aria-label="' . $this->escape($label) . '" hidden tabindex="-1">'
            . '<header><h2>' . $this->escape($label) . '</h2><button class="mgd-a11y__close" type="button" aria-label="Schließen">×</button></header><p>Darstellung nur für dieses Gerät anpassen.</p>'
            . '<div class="mgd-a11y__actions"><button type="button" data-mgd-action="contrast">Kontrast</button><button type="button" data-mgd-action="gray">Graustufen</button><button type="button" data-mgd-action="focus">Fokus</button><button type="button" data-mgd-action="font-up">Schrift +</button><button type="button" data-mgd-action="font-down">Schrift −</button><button type="button" data-mgd-action="motion">Bewegung stoppen</button><button type="button" data-mgd-action="reset">Zurücksetzen</button></div>'
            . ($statement === '' ? '' : '<p><a href="' . $this->escape($statement) . '">Erklärung zur Barrierefreiheit</a></p>')
            . '<p class="mgd-a11y__credit"><a href="https://michael-gahn.de" target="_blank" rel="noopener noreferrer">Michael-Gahn.de</a></p></section></div>';
        $this->append($document, 'head', '<link rel="stylesheet" href="' . $this->escape($base . '/css/mgd-barrierefrei.css') . '" data-mgd-barrierefrei>');
        $this->append($document, 'body', $markup . '<script src="' . $this->escape($base . '/js/mgd-barrierefrei.js') . '" defer data-mgd-barrierefrei></script>');
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
        return is_array($parts) && ($parts['scheme'] ?? '') === 'https' && is_string($parts['host'] ?? null) && ($parts['host'] ?? '') !== '';
    }

    private function safeStatementUrl(string $url): string
    {
        $url = trim($url);
        if (preg_match('#^/[A-Za-z0-9._~:/?&=%+-]*$#D', $url) === 1) return $url;
        $parts = parse_url($url);
        return is_array($parts) && ($parts['scheme'] ?? '') === 'https' && is_string($parts['host'] ?? null) ? $url : '';
    }

    private function text(string $value, string $fallback): string
    {
        $value = trim($value);
        return $value === '' ? $fallback : mb_substr($value, 0, 80);
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
