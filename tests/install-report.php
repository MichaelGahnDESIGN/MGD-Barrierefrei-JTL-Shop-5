<?php

declare(strict_types=1);

// Free-Lizenz: Client baut sich, Schalter auf der Update-Seite, Kurztext in info.xml
spl_autoload_register(static function (string $c): void {
    $p = 'Plugin\\MGD_Barrierefrei\\';
    if (str_starts_with($c, $p)) {
        $f = __DIR__ . '/../plugin/MGD_Barrierefrei/' . str_replace('\\', '/', substr($c, strlen($p))) . '.php';
        if (is_file($f)) {
            require $f;
        }
    }
});

use Plugin\MGD_Barrierefrei\Install\Platform\Jtl;
use Plugin\MGD_Barrierefrei\Install\Settings;
use Plugin\MGD_Barrierefrei\Presentation\UpdatesTab;
use Plugin\MGD_Barrierefrei\UpdateClient\Admin\InstallReporting;
use Plugin\MGD_Barrierefrei\UpdateClient\Admin\UpdatesPage;
use Plugin\MGD_Barrierefrei\UpdateClient\Factory;

$fail = 0;
$check = static function (bool $ok, string $name) use (&$fail): void {
    echo ($ok ? 'OK   ' : 'FAIL ') . $name . "\n";
    $fail += $ok ? 0 : 1;
};

$dir = sys_get_temp_dir() . '/mgd-bf-install-' . bin2hex(random_bytes(4));
mkdir($dir, 0700);

$ic = Jtl::client($dir, UpdatesTab::UPDATE_PRODUCT, '1.5.0', 'demo.example');
$check($ic !== null, 'Client baut sich mit dem Produktnamen der Update-Datei');
$check($ic !== null && $ic->isEnabled(), 'Meldung ist ab Werk an');
$check(Jtl::client($dir, 'MGD Barrierefrei', '1.5.0', 'demo.example') === null, 'Falscher Produktname gibt null statt Fehler');
$check(!str_contains(Settings::HINT, 'ENTWURF') && str_contains(Settings::HINT, 'theme.michael-gahn.de'), 'Freigegebener Hinweistext');

// Schalter aus = nichts mehr senden (ohne Schlüssel kein DELETE nötig)
$ic->setEnabled(false);
$check(!$ic->isEnabled() && $ic->ping(true) === false, 'Abgeschaltet sendet nichts');
$ic->setEnabled(true);
$check($ic->isEnabled(), 'Wieder einschalten klappt');

$install = new InstallReporting(fn () => $ic->ping(), fn () => $ic->isEnabled(), fn (bool $an) => $ic->setEnabled($an), fn () => $ic->status(), Settings::HINT);
$page = Factory::page(__DIR__ . '/../plugin/MGD_Barrierefrei/update/root-keys.json', $dir, 'mgd-barrierefrei', 'free', 'MGD Barrierefrei', '1.5.0', 'demo.example', '5.7.2', fn () => true, fn () => true, '<input type="hidden" name="jtl_token" value="x">', null, ['info.xml'], UpdatesTab::SIMPLE_URL, $install);
$html = $page->handle('GET', [])['html'];
$check(str_contains($html, 'Installation bei Michael Gahn DESIGN melden') && str_contains($html, 'save_install'), 'Schalter steht auf der Update-Seite');
$page->handle('POST', ['mgd_update_action' => 'save_install']);
$check(!$ic->isEnabled(), 'Haken weg + Speichern schaltet aus');
$page->handle('POST', ['mgd_update_action' => 'save_install', 'install_report' => '1']);
$check($ic->isEnabled(), 'Haken setzen schaltet wieder an');

$xml = (string) file_get_contents(__DIR__ . '/../plugin/MGD_Barrierefrei/info.xml');
$check(str_contains($xml, 'Meldet sich höchstens einmal am Tag bei theme.michael-gahn.de (Plugin, Version, Shopsystem, Domain, zufällige ID). Abschaltbar in den Einstellungen.'), 'Kurztext in info.xml');

array_map('unlink', glob($dir . '/*') ?: []);
@rmdir($dir);
exit($fail === 0 ? 0 : 1);
