<?php

declare(strict_types=1);

use JTL\Plugin\PluginInterface;
use Plugin\MGD_Barrierefrei\Kit\Platform\Jtl\Setup;

// Tab Weitere Plugins vom Baukasten - neue Ansicht, alte CatalogTab nicht mehr angemeldet
if (!defined('PFAD_ROOT') || !isset($oPlugin) || !$oPlugin instanceof PluginInterface) {
    http_response_code(403);
    return;
}
echo Setup::renderTab(Setup::forShop($oPlugin), 'Weitere Plugins');
