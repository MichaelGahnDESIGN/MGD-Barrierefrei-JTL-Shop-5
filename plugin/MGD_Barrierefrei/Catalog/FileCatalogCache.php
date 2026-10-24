<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Catalog;

/** Datei-Cache im Cache-Ordner des Shops (templates_c); kaputt oder nicht schreibbar = einfach kein Cache. */
final class FileCatalogCache implements CatalogCache
{
    private readonly string $file;

    public function __construct(string $directory)
    {
        // Hash im Namen, falls mehrere Shops denselben Ordner nutzen
        $this->file = rtrim($directory, '/\\') . '/mgd_barrierefrei_catalog_' . substr(md5($directory), 0, 8) . '.json';
    }

    /** Standard-Ordner: templates_c des Shops, sonst der Temp-Ordner */
    public static function forShop(): self
    {
        $directory = defined('PFAD_ROOT') && defined('PFAD_COMPILEDIR') ? PFAD_ROOT . PFAD_COMPILEDIR : '';
        if ($directory === '' || !is_dir($directory) || !is_writable($directory)) {
            $directory = sys_get_temp_dir();
        }

        return new self($directory);
    }

    public function get(): ?array
    {
        $raw = is_file($this->file) ? @file_get_contents($this->file, false, null, 0, 1_048_576) : false;
        if (!is_string($raw)) {
            return null;
        }
        $data = json_decode($raw, true, 32);
        if (!is_array($data) || !is_int($data['expires'] ?? null) || $data['expires'] < time() || !is_array($data['record'] ?? null)) {
            return null;
        }

        return $data['record'];
    }

    public function set(array $record, int $ttlSeconds): void
    {
        $json = json_encode(['expires' => time() + $ttlSeconds, 'record' => $record]);
        $directory = dirname($this->file);
        if ($json === false || !is_dir($directory) || !is_writable($directory)) {
            return;
        }
        // erst in Temp-Datei, dann umbenennen (kein halbes Schreiben)
        $tmp = @tempnam($directory, 'mgdcat');
        if ($tmp === false) {
            return;
        }
        if (@file_put_contents($tmp, $json) === false || !@rename($tmp, $this->file)) {
            @unlink($tmp);
        }
    }
}
