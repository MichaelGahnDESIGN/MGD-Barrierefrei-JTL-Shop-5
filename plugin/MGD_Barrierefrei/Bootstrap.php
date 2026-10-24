<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\MGD_Barrierefrei\Presentation\CatalogTab;
use Plugin\MGD_Barrierefrei\Presentation\FrontendDocumentIntegrator;

/** Bindet ausschließlich lokale Assets an das von JTL erzeugte Dokument an. */
final class Bootstrap extends Bootstrapper
{
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
        if (!Shop::isFrontend()) {
            return;
        }

        $dispatcher->listen('shop.hook.140', function (array $arguments): void {
            $plugin = $this->getPlugin();
            $config = $plugin->getConfig();
            if ($config->getValue('enabled') !== 'Y') {
                return;
            }
            (new FrontendDocumentIntegrator())->integrate(
                $arguments,
                $plugin->getPaths()->getFrontendURL(),
                [
                    'position' => (string) $config->getValue('position'),
                    'label' => (string) $config->getValue('button_label'),
                    'accent' => (string) $config->getValue('accent_color'),
                    'statementUrl' => (string) $config->getValue('statement_url'),
                ],
            );
        });
    }

    // Tab "Weitere Plugins" im Admin (Smarty-Template), nur Admin, nie im Frontend
    public function renderAdminMenuTab(string $tabName, int $menuID, JTLSmarty $smarty): string
    {
        if ($tabName !== CatalogTab::TAB_NAME) {
            return parent::renderAdminMenuTab($tabName, $menuID, $smarty);
        }

        return (new CatalogTab())->render($smarty);
    }
}
