<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei;

use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use JTL\Shop;
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
}
