<?php

declare(strict_types=1);

// Meldung nach Download: einfacher Modus darf keine Signatur behaupten
spl_autoload_register(static function (string $c): void {
    $p = 'Plugin\\MGD_Barrierefrei\\';
    if (str_starts_with($c, $p)) {
        $f = __DIR__ . '/../plugin/MGD_Barrierefrei/' . str_replace('\\', '/', substr($c, strlen($p))) . '.php';
        if (is_file($f)) {
            require $f;
        }
    }
});

use Plugin\MGD_Barrierefrei\UpdateClient\Admin\UpdatesPage;
use Plugin\MGD_Barrierefrei\UpdateClient\UpdateResult;

$simple = UpdatesPage::verifiedText(new UpdateResult('update_available', '', null, null, ['mode' => 'simple']));
$signed = UpdatesPage::verifiedText(new UpdateResult('update_available', ''));
$ok = !str_contains($simple, 'Signatur') && str_contains($simple, 'SHA-256') && str_contains($signed, 'Signatur');
echo ($ok ? 'OK   ' : 'FAIL ') . "Meldung nennt nur, was geprüft wurde\n";
exit($ok ? 0 : 1);
