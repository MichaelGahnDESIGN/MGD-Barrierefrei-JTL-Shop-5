<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

/** Datenordner für den Update-Client (Zustand, geprüfte ZIPs). Liegt in templates_c, nicht im Plugin-Ordner. */
final class UpdateDataDir
{
    public const FOLDER = 'mgd_barrierefrei_update';

    // Datenordner ausserhalb Web: templates_c des Shops
    public static function forShop(): ?string
    {
        if (!defined('PFAD_ROOT') || !defined('PFAD_COMPILEDIR')) {
            return null;
        }

        return self::prepare(PFAD_ROOT . PFAD_COMPILEDIR);
    }

    /** legt Unterordner an + Zugriffssperre; null = nicht möglich */
    public static function prepare(string $base): ?string
    {
        $base = rtrim($base, '/\\');
        if ($base === '' || !is_dir($base) || !is_writable($base)) {
            return null;
        }
        $dir = $base . '/' . self::FOLDER;
        if (!is_dir($dir) && !@mkdir($dir, 0700, true) && !is_dir($dir)) {
            return null;
        }
        // .htaccess Deny, falls der Ordner doch im Web liegt (Apache 2.2 + 2.4)
        $deny = "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nOrder allow,deny\nDeny from all\n</IfModule>\n";
        if (!is_file($dir . '/.htaccess')) {
            @file_put_contents($dir . '/.htaccess', $deny);
        }
        if (!is_file($dir . '/index.html')) {
            @file_put_contents($dir . '/index.html', '');
        }

        return is_writable($dir) ? $dir : null;
    }
}
