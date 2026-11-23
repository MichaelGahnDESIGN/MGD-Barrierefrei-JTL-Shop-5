<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
use Plugin\MGD_Barrierefrei\Kit\Platform\Jtl\Setup;
use Plugin\MGD_Barrierefrei\Presentation\FrontendDocumentIntegrator;

/** Bindet ausschließlich lokale Assets an das von JTL erzeugte Dokument an. */
final class Bootstrap extends Bootstrapper
{
    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);
        if (!Shop::isFrontend()) {
            // Aktiv-Meldung max 1x am Tag, nur im Admin - jetzt übern Baukasten
            if (PHP_SAPI !== 'cli') {
                Setup::forShop($this->getPlugin())?->ping();
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

    // Free-Lizenz anmelden bei Installieren/Aktivieren/Update (Baukasten)
    public function installed()
    {
        parent::installed();
        Setup::forShop($this->getPlugin())?->ping(true);
    }

    public function enabled()
    {
        parent::enabled();
        Setup::forShop($this->getPlugin())?->ping(true);
    }

    public function updated($oldVersion, $newVersion)
    {
        parent::updated($oldVersion, $newVersion);
        Setup::forShop($this->getPlugin())?->ping(true);
    }

    // Deinstallieren: bei theme.michael-gahn.de abmelden, Datenordner nur bei "Daten löschen" weg
    public function uninstalled(bool $deleteData = true)
    {
        Setup::forShop($this->getPlugin())?->uninstall($deleteData);
        parent::uninstalled($deleteData);
    }
}
