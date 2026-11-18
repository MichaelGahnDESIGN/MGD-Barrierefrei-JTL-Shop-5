<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use JTL\Smarty\JTLSmarty;
use Plugin\MGD_Barrierefrei\Presentation\CatalogTab;
use Plugin\MGD_Barrierefrei\Presentation\FrontendDocumentIntegrator;
use Plugin\MGD_Barrierefrei\Presentation\InstallReport;

/** Bindet ausschließlich lokale Assets an das von JTL erzeugte Dokument an. */
final class Bootstrap extends Bootstrapper
{
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
        if (!Shop::isFrontend()) {
            // Aktiv-Meldung max 1x am Tag, nur im Admin
            if (PHP_SAPI !== 'cli') {
                InstallReport::ping($this->getPlugin());
            }

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
                    'labelEn' => (string) $config->getValue('button_label_en'),
                    'accent' => (string) $config->getValue('accent_color'),
                    'statementUrl' => (string) $config->getValue('statement_url'),
                    'lang' => (string) $config->getValue('default_lang'),
                    'theme' => (string) $config->getValue('default_theme'),
                    'offset' => (string) $config->getValue('offset'),
                ],
            );
        });
    }

    // Free-Lizenz anmelden bei Installieren/Aktivieren/Update
    public function installed()
    {
        parent::installed();
        InstallReport::ping($this->getPlugin(), true);
    }

    public function enabled()
    {
        parent::enabled();
        InstallReport::ping($this->getPlugin(), true);
    }

    public function updated($oldVersion, $newVersion)
    {
        parent::updated($oldVersion, $newVersion);
        InstallReport::ping($this->getPlugin(), true, is_string($newVersion) ? $newVersion : null);
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
