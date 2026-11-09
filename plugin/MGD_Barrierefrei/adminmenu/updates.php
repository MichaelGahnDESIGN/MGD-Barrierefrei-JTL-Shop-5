<?php

declare(strict_types=1);

use JTL\Plugin\PluginInterface;
use Plugin\MGD_Barrierefrei\Presentation\UpdatesTab;

if (!defined('PFAD_ROOT') || !isset($oPlugin) || !$oPlugin instanceof PluginInterface) {
    http_response_code(403);
    echo 'Dieser Bereich ist nur in der JTL-Administration verfügbar.';
    return;
}

// Smarty aus dem Admin
$engine = $smarty ?? ($GLOBALS['smarty'] ?? null);
if (!is_object($engine)) {
    echo '<p>Der Update-Bereich kann gerade nicht angezeigt werden (Template-Engine fehlt).</p>';
    return;
}
// Updates Menue
$updatesTab = UpdatesTab::forShop($oPlugin);
if ($updatesTab === null) {
    echo '<p>Der Update-Bereich kann gerade nicht angezeigt werden (Datenordner in templates_c nicht beschreibbar). Der Shop läuft normal weiter.</p>';
    return;
}
echo $updatesTab->render($engine, (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'), $_POST);
