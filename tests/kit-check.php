<?php

declare(strict_types=1);

// Brückenversion: Baukasten drin, Tabs Übersicht + Weitere Plugins, alte Tabs abgemeldet, Abmelden beim Deinstallieren
$root = dirname(__DIR__) . '/plugin/MGD_Barrierefrei';
$fail = 0;
$check = static function (bool $ok, string $name) use (&$fail): void {
    echo ($ok ? 'OK   ' : 'FAIL ') . $name . "\n";
    $fail += $ok ? 0 : 1;
};
$read = static fn (string $f): string => (string) file_get_contents($root . '/' . $f);
$info = $read('info.xml');
$boot = $read('Bootstrap.php');
$config = $read('Kit/Platform/Jtl/Config.php');

$check(is_file($root . '/Kit/Platform/Jtl/Setup.php') && is_file($root . '/Kit/Platform/Jtl/JtlKit.php'), 'Baukasten liegt unter Kit/');
$check(str_contains($config, "PRODUCT = 'mgd-barrierefrei'") && str_contains($config, "PLUGIN_ID = 'MGD_Barrierefrei'") && str_contains($config, "CATALOG_SLUG = 'plugin-barrierefrei-jtl'"), 'Produktname wie Update-Datei, Plugin-ID, Katalog-Kennung');
$check(str_contains($info, '<Name>Übersicht</Name><Filename>uebersicht.php</Filename>') && str_contains($info, '<Name>Weitere Plugins</Name><Filename>weitere-plugins.php</Filename>'), 'Tabs Übersicht + Weitere Plugins in info.xml');
$check(!str_contains($info, 'updates.php') && !is_file($root . '/adminmenu/updates.php'), 'alter Tab Updates abgemeldet');
foreach (['uebersicht.php' => 'Übersicht', 'weitere-plugins.php' => 'Weitere Plugins'] as $f => $tab) {
    $c = $read('adminmenu/' . $f);
    $check(str_contains($c, "Setup::renderTab(Setup::forShop(\$oPlugin), '$tab')") && str_contains($c, "http_response_code(403)"), "adminmenu/$f nutzt den Baukasten");
}
$check(!str_contains($boot, 'CatalogTab') && !str_contains($boot, 'InstallReport::'), 'alte Liste + alter Melder nicht mehr angemeldet');
$check(substr_count($boot, '?->ping(true)') === 3 && str_contains($boot, 'Setup::forShop($this->getPlugin())?->ping();') && str_contains($boot, 'Setup::isBackend()') && str_contains($boot, "['version' => \$newVersion]"), 'Meldung bei Install/Aktivieren/Update + 1x am Tag im Admin');
$check(str_contains($boot, 'Setup::forShop($this->getPlugin())?->uninstall($deleteData);'), 'Abmelden beim Deinstallieren');
$check((bool) preg_match('~<Version>2\.1\.1</Version>~', $info), 'Version 2.1.1');
$left = 0;
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/Kit', FilesystemIterator::SKIP_DOTS)) as $f) {
    $left += str_contains((string) file_get_contents((string) $f), '{{KIT_') ? 1 : 0;
}
$check($left === 0, 'keine Platzhalter im Baukasten');
exit($fail === 0 ? 0 : 1);
