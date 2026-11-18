<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

use JTL\Shop;
use Plugin\MGD_Barrierefrei\Install\InstallClient;
use Plugin\MGD_Barrierefrei\Install\Platform\Jtl;
use Plugin\MGD_Barrierefrei\Install\Settings;
use Plugin\MGD_Barrierefrei\UpdateClient\Admin\InstallReporting;

// Free-Lizenz Anmeldung bei theme.michael-gahn.de - nur Admin/Bootstrap, nie im Frontend
final class InstallReport
{
    /** null = Datenordner/Version nicht nutzbar, dann eben keine Meldung */
    public static function client(object $plugin, ?string $version = null): ?InstallClient
    {
        try {
            $dir = UpdateDataDir::forShop();
            $v = $version ?? UpdatesTab::installedVersion($plugin);
            if ($dir === null || preg_match('/^\d+\.\d+\.\d+$/', (string) $v) !== 1) {
                return null;
            }
            $host = strtolower((string) parse_url(Shop::getURL(), PHP_URL_HOST));

            return Jtl::client($dir, UpdatesTab::UPDATE_PRODUCT, (string) $v, $host !== '' ? $host : null);
        } catch (\Throwable) {
            return null;
        }
    }

    // darf nie die Installation oder den Admin killen
    public static function ping(object $plugin, bool $force = false, ?string $version = null): void
    {
        try {
            self::client($plugin, $version)?->ping($force);
        } catch (\Throwable) {
        }
    }

    // Schalter für die Update-Seite
    public static function reporting(object $plugin): ?InstallReporting
    {
        $ic = self::client($plugin);
        if ($ic === null) {
            return null;
        }

        return new InstallReporting(
            static fn () => $ic->ping(),
            static fn () => $ic->isEnabled(),
            static fn (bool $an) => $ic->setEnabled($an),
            static fn () => $ic->status(),
            Settings::HINT,
        );
    }
}
