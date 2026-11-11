<?php

declare(strict_types=1);

// Test Widget-Config: php tests/frontend-config.php
require __DIR__ . '/../plugin/MGD_Barrierefrei/Presentation/FrontendDocumentIntegrator.php';

use Plugin\MGD_Barrierefrei\Presentation\FrontendDocumentIntegrator as F;

$fail = 0;
$check = static function (bool $ok, string $msg) use (&$fail): void {
    echo ($ok ? 'OK   ' : 'FAIL ') . $msg . "\n";
    $fail += $ok ? 0 : 1;
};

$c = F::config([]);
$check($c['position'] === 'left' && $c['lang'] === 'auto' && $c['theme'] === 'auto' && $c['accent'] === '#b91c1c', 'Defaults');

$c = F::config(['position' => 'right', 'lang' => 'en', 'theme' => 'dark', 'accent' => '#00ff00', 'offset' => '80']);
$check($c['position'] === 'right' && $c['lang'] === 'en' && $c['theme'] === 'dark' && $c['accent'] === '#00ff00' && $c['offset'] === 80, 'Gültige Werte bleiben');

$c = F::config(['position' => 'top', 'lang' => 'fr', 'theme' => 'pink', 'accent' => 'red;}', 'offset' => '9999']);
$check($c['position'] === 'left' && $c['lang'] === 'auto' && $c['theme'] === 'auto' && $c['accent'] === '#b91c1c' && $c['offset'] === 200, 'Ungültige Werte > Standard');

$check(F::config(['statementUrl' => 'javascript:alert(1)'])['statementUrl'] === '', 'javascript: URL blockiert');
$check(F::config(['statementUrl' => '//evil.example'])['statementUrl'] === '', 'Protokoll-relative URL blockiert');
$check(F::config(['statementUrl' => '/barrierefreiheit'])['statementUrl'] === '/barrierefreiheit', 'Relative URL erlaubt');
$check(F::config(['statementUrl' => 'https://shop.example/a11y'])['statementUrl'] === 'https://shop.example/a11y', 'HTTPS URL erlaubt');

$c = F::config(['label' => 'Barrierefreiheit', 'labelEn' => 'Accessibility']);
$check($c['labels'] === ['de' => '', 'en' => ''], 'Standard-Beschriftung > Übersetzung im Widget');
$c = F::config(['label' => '<b>Hilfe</b></script>', 'labelEn' => str_repeat('x', 200)]);
$check($c['labels']['de'] === 'Hilfe' && mb_strlen($c['labels']['en']) === 80, 'Beschriftung ohne HTML, max 80 Zeichen');

$json = json_encode(F::config(['label' => '</script><script>alert(1)</script>x']), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$check(!str_contains((string) $json, '</script'), 'JSON kann Script-Tag nicht schließen');

exit($fail > 0 ? 1 : 0);
