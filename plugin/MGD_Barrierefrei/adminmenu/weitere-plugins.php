<?php

declare(strict_types=1);

use JTL\Plugin\PluginInterface;
use Plugin\MGD_Barrierefrei\Presentation\CatalogTab;

if (!defined('PFAD_ROOT') || !isset($oPlugin) || !$oPlugin instanceof PluginInterface) {
    http_response_code(403);
    echo 'Dieser Bereich ist nur in der JTL-Administration verfügbar.';
    return;
}

// Smarty aus dem Admin, sonst Hinweis statt Fehlerseite
$engine = $smarty ?? ($GLOBALS['smarty'] ?? null);
if (!is_object($engine)) {
    echo '<p>Die Liste kann gerade nicht angezeigt werden (Template-Engine fehlt).</p>';
    return;
}
echo (new CatalogTab())->render($engine);
