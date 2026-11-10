<?php

declare(strict_types=1);

// Smoke-Test Update-Bereich ohne Composer/JTL: php tests/update-smoke.php
// Fake-Callbacks + Fake-HTTP. Kein Netz, keine echten Schlüssel, keine Lizenz-Token.

use Plugin\MGD_Barrierefrei\Presentation\UpdateDataDir;
use Plugin\MGD_Barrierefrei\Presentation\UpdatesTab;
use Plugin\MGD_Barrierefrei\UpdateClient\Admin\UpdatesPage;
use Plugin\MGD_Barrierefrei\UpdateClient\ClientConfig;
use Plugin\MGD_Barrierefrei\UpdateClient\Crypto\KeyRing;
use Plugin\MGD_Barrierefrei\UpdateClient\Factory;
use Plugin\MGD_Barrierefrei\UpdateClient\Http\HttpClient;
use Plugin\MGD_Barrierefrei\UpdateClient\Http\HttpResponse;
use Plugin\MGD_Barrierefrei\UpdateClient\License\NullLicenseProvider;
use Plugin\MGD_Barrierefrei\UpdateClient\Store\JsonFileStateStore;
use Plugin\MGD_Barrierefrei\UpdateClient\UpdateClient;

$base = __DIR__ . '/../plugin/MGD_Barrierefrei';
// Autoloader wie JTL: Plugin\MGD_Barrierefrei\X > plugin/MGD_Barrierefrei/X.php
spl_autoload_register(static function (string $class) use ($base): void {
    $prefix = 'Plugin\\MGD_Barrierefrei\\';
    if (str_starts_with($class, $prefix)) {
        $file = $base . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

$failed = 0;
$total = 0;
function check(bool $ok, string $name): void
{
    global $failed, $total;
    ++$total;
    if (!$ok) {
        ++$failed;
        fwrite(STDERR, "FEHLER: $name\n");
    }
}

// zählt Netzzugriffe, antwortet immer 500
final class CountingHttp implements HttpClient
{
    public int $calls = 0;

    public function request(string $method, string $url, array $headers = [], ?string $body = null, int $maxBytes = 1048576): HttpResponse
    {
        ++$this->calls;
        return new HttpResponse(500);
    }

    public function download(string $url, array $headers, string $targetFile, int $maxBytes): HttpResponse
    {
        ++$this->calls;
        return new HttpResponse(500);
    }
}

function tmpdir(): string
{
    $d = sys_get_temp_dir() . '/mgd-upd-test-' . bin2hex(random_bytes(4));
    mkdir($d, 0700);
    return $d;
}

function rmtree(string $d): void
{
    foreach (glob($d . '/{,.}*', GLOB_BRACE) ?: [] as $f) {
        if (in_array(basename($f), ['.', '..'], true)) {
            continue;
        }
        is_dir($f) ? rmtree($f) : unlink($f);
    }
    @rmdir($d);
}

/** @return array{0:UpdatesPage,1:CountingHttp,2:JsonFileStateStore} */
function makePage(string $dir, bool $csrf, bool $allowed, string $name = 'MGD Barrierefrei', string $version = '1.1.0', ?callable $clock = null): array
{
    $ring = (new KeyRing())->add('root-test-1', 'root', str_repeat("\x01", 32));
    $cfg = new ClientConfig(UpdatesTab::UPDATE_PRODUCT, 'free', $version, 'shop.example', $ring, $dir, '5.8.2');
    $http = new CountingHttp();
    $store = new JsonFileStateStore($dir . '/state.json');
    $client = new UpdateClient($cfg, $http, new NullLicenseProvider(), $store);
    $page = new UpdatesPage($client, $store, static fn (): bool => $csrf, static fn (): bool => $allowed, '<input type="hidden" name="jtl_token" value="TEST">', $name, $version, $clock);

    return [$page, $http, $store];
}

$dir = tmpdir();

// --- ohne Recht: kein Zugriff, kein Netz, auch nicht bei POST
[$page, $http] = makePage($dir, true, false);
$r = $page->handle('GET', []);
check(str_contains($r['html'], 'Berechtigung') && !str_contains($r['html'], '<form'), 'ohne Recht: nur Hinweis, kein Formular');
$r = $page->handle('POST', ['mgd_update_action' => 'check_now']);
check(str_contains($r['html'], 'Berechtigung') && $http->calls === 0 && $r['deliver'] === null, 'ohne Recht: POST check_now macht nichts');
$r = $page->handle('POST', ['mgd_update_action' => 'deliver', 'confirm' => '1']);
check($http->calls === 0 && $r['deliver'] === null, 'ohne Recht: kein Download/Deliver');

// --- ohne CSRF: keine Aktion
[$page, $http, $store] = makePage($dir, false, true);
foreach (['check_now', 'save_settings', 'download', 'deliver'] as $action) {
    $r = $page->handle('POST', ['mgd_update_action' => $action, 'confirm' => '1', 'checks_enabled' => '1']);
    check(str_contains($r['html'], 'Sicherheits-Token') && $r['deliver'] === null, "ohne CSRF: Aktion $action abgelehnt");
}
check($http->calls === 0, 'ohne CSRF: kein Netzabruf');
check(($store->load()['enabled'] ?? false) !== true, 'ohne CSRF: Einstellung nicht gespeichert');
// GET mit Aktions-Parameter zählt nicht
$r = $page->handle('GET', ['mgd_update_action' => 'check_now']);
check($http->calls === 0, 'GET mit mgd_update_action löst nichts aus');

// --- GET ohne Einschalten: kein Netz
[$page, $http, $store] = makePage($dir, true, true);
$r = $page->handle('GET', []);
check($http->calls === 0, 'GET ohne Einschalten: kein Netzabruf');
check(str_contains($r['html'], 'name="jtl_token"') && str_contains($r['html'], 'checks_enabled'), 'GET: Formular mit CSRF-Feld und Opt-in');
check(!str_contains($r['html'], 'checked'), 'Opt-in ist standardmäßig aus');

// --- Kontrolle: mit Einschalten + alter Prüfung wird abgerufen (beweist, dass der Zähler greift)
$page->handle('POST', ['mgd_update_action' => 'save_settings', 'checks_enabled' => '1']);
check(($store->load()['enabled'] ?? false) === true, 'Einschalten (CSRF ok) wird gespeichert');
$http->calls = 0;
$page->handle('GET', []);
check($http->calls >= 1, 'Kontrolle: eingeschaltet + nie geprüft > Abruf bei GET');
$http->calls = 0;
$page->handle('GET', []);
check($http->calls === 0, 'danach: innerhalb 12 Stunden kein neuer Abruf');

// --- Klick auf "Jetzt suchen" (CSRF ok) = genau ein Abrufversuch-Block
$http->calls = 0;
$r = $page->handle('POST', ['mgd_update_action' => 'check_now']);
check($http->calls >= 1 && str_contains($r['html'], 'HTTP 500'), 'check_now mit CSRF ruft ab (Fehler wird angezeigt)');

// --- Download braucht Bestätigung
$http->calls = 0;
$r = $page->handle('POST', ['mgd_update_action' => 'download']);
check($http->calls === 0 && str_contains($r['html'], 'Bestätigung'), 'Download ohne Bestätigung: nichts passiert');
$r = $page->handle('POST', ['mgd_update_action' => 'deliver']);
check($http->calls === 0 && $r['deliver'] === null, 'Deliver ohne Bestätigung: nichts passiert');
$r = $page->handle('POST', ['mgd_update_action' => 'gibt-es-nicht']);
check($r['deliver'] === null, 'unbekannte Aktion tut nichts');

// --- Anzeige escaped (Pluginname, Version, Meldung, Changelog)
$evil = '<script>alert(1)</script>';
$dirB = tmpdir();
[$page, , $storeB] = makePage($dirB, true, true, '<b>' . $evil . '</b>', '1.1.0');
$storeB->save([
    'enabled' => true, 'last_check' => time(),
    'last_result' => ['status' => 'update_available', 'message' => $evil, 'version' => '"><img src=x onerror=1>', 'changelog' => $evil, 'security' => true],
]);
$html = $page->handle('GET', [])['html'];
check(!str_contains($html, '<script') && !str_contains($html, '<img') && !str_contains($html, '<b>'), 'Anzeige: kein ungefiltertes HTML');
check(substr_count($html, '&lt;script&gt;') >= 3 && str_contains($html, '&quot;&gt;&lt;img'), 'Anzeige: Meldung, Changelog, Version, Name sind escaped');
check(str_contains($html, 'Es wird nichts automatisch installiert') && str_contains($html, 'JTL-Pluginverwaltung'), 'Hinweis: nur Anleitung zur JTL-Pluginverwaltung');
// Konstante Formularfelder bleiben (nur unser eigenes Markup)
check(substr_count($html, 'name="jtl_token"') >= 3, 'Formulare tragen das CSRF-Feld');

// --- echte Factory, leere Schlüsselliste: "nicht eingerichtet", kein Netz
$dirC = tmpdir();
$keysFile = $base . '/update/root-keys.json';
$keys = json_decode((string) file_get_contents($keysFile), true);
check(is_array($keys) && ($keys['keys'] ?? null) === [], 'root-keys.json: aktuell leer (Blocker B1)');
check(!preg_match('/private|secret|seed/i', (string) file_get_contents($keysFile)), 'root-keys.json: keine privaten Teile');
$real = Factory::page($keysFile, $dirC, UpdatesTab::UPDATE_PRODUCT, UpdatesTab::UPDATE_EDITION, UpdatesTab::PLUGIN_NAME, '1.1.0', 'shop.example', '5.8.2', static fn (): bool => true, static fn (): bool => true, '<input type="hidden" name="jtl_token" value="T">');
$r = $real->handle('POST', ['mgd_update_action' => 'check_now']);
check(str_contains($r['html'], 'noch nicht eingerichtet'), 'echte Factory + leere Schlüssel: Update-Kanal noch nicht eingerichtet');

// --- Signatur von Factory::page wie erwartet
$params = array_map(static fn (ReflectionParameter $p): string => $p->getName(), (new ReflectionMethod(Factory::class, 'page'))->getParameters());
check($params === ['rootKeysFile', 'dataDir', 'product', 'edition', 'pluginName', 'installedVersion', 'domain', 'shopVersion', 'csrfValid', 'canManage', 'csrfField', 'license', 'requiredFiles', 'simpleUrl'], 'Factory::page: Parameter wie in UpdatesTab verwendet');
// einfacher Modus verdrahtet (Prüfsummen-Datei), nur statisch geprüft > kein Netz im Test
check(UpdatesTab::SIMPLE_URL === 'https://theme.michael-gahn.de/plugin-updates/mgd-barrierefrei.json', 'einfacher Modus: Adresse der Prüfsummen-Datei');
check(str_contains((string) file_get_contents($base . '/Presentation/UpdatesTab.php'), 'self::SIMPLE_URL,'), 'einfacher Modus: UpdatesTab gibt die Adresse weiter');
check(UpdatesTab::UPDATE_PRODUCT === 'mgd-barrierefrei' && UpdatesTab::UPDATE_EDITION === 'free', 'Produktname/Edition als Konstante');

// --- UpdatesTab: Template und Auslieferung
$fake = new class {
    public array $assigned = [];
    public ?string $template = null;

    public function assign(string $k, mixed $v): self
    {
        $this->assigned[$k] = $v;
        return $this;
    }

    public function fetch(string $t): string
    {
        $this->template = $t;
        return 'TPL';
    }
};
$sent = [];
$dirD = tmpdir();
[$page] = makePage($dirD, true, true);
$tab = new UpdatesTab($page, static function (string $f) use (&$sent): void {
    $sent[] = $f;
});
$out = $tab->render($fake, 'GET', []);
check($out === 'TPL' && is_file((string) $fake->template) && is_string($fake->assigned['mgdUpdates']['html'] ?? null) && $sent === [], 'Tab: Template-Datei da, HTML übergeben, kein Download');
$tpl = (string) file_get_contents($base . '/adminmenu/templates/updates.tpl');
preg_match_all('/\{\$[^}]+\}/', $tpl, $m);
$unsafe = array_filter($m[0], static fn (string $t): bool => !str_contains($t, '|escape:') && !str_contains($t, 'nofilter'));
check($unsafe === [] && substr_count($tpl, 'nofilter') === 1 && !str_contains($tpl, '<script'), 'Template: nur das fertig escapte HTML mit nofilter, kein Skript');

// --- Version aus Plugin-Objekt bzw. info.xml
$plug = new class {
    public function getMeta(): object
    {
        return new class {
            public function getVersion(): string
            {
                return '9.8.7';
            }
        };
    }
};
check(UpdatesTab::installedVersion($plug) === '9.8.7', 'Version kommt aus dem Plugin-Objekt');
$xml = simplexml_load_file($base . '/info.xml');
check(UpdatesTab::installedVersion(new stdClass()) === (string) $xml->Version, 'Fallback: Version aus info.xml');
$bad = new class {
    public function getMeta(): object
    {
        return new class {
            public function getVersion(): string
            {
                return 'dev-main';
            }
        };
    }
};
check(UpdatesTab::installedVersion($bad) === '', 'ungültige Version wird abgelehnt');
check(!preg_match('/1\.1\.0/', (string) file_get_contents($base . '/Presentation/UpdatesTab.php')), 'Version nicht fest im Code');

// --- Datenordner
$baseDir = tmpdir();
$d = UpdateDataDir::prepare($baseDir);
check($d !== null && is_dir($d) && is_file($d . '/.htaccess') && str_contains((string) file_get_contents($d . '/.htaccess'), 'denied'), 'Datenordner: angelegt mit .htaccess Deny');
check(is_file($d . '/index.html'), 'Datenordner: index.html');
check(UpdateDataDir::prepare($baseDir . '/gibt-es-nicht') === null, 'Datenordner: Basis fehlt > null');
check(UpdateDataDir::forShop() === null, 'Datenordner: ohne JTL-Konstanten > null');

// --- Admin-Datei: Guard ohne JTL
check(str_contains((string) file_get_contents($base . '/adminmenu/updates.php'), 'instanceof PluginInterface'), 'updates.php: Guard gegen direkten Aufruf');

foreach ([$dir, $dirB, $dirC, $dirD, $baseDir] as $x) {
    rmtree($x);
}

echo ($failed === 0 ? 'OK' : 'FEHLGESCHLAGEN') . ": $total Prüfungen, $failed Fehler\n";
exit($failed === 0 ? 0 : 1);
