<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

use JTL\Helpers\Form;
use JTL\Shop;
use Plugin\MGD_Barrierefrei\UpdateClient\Admin\UpdatesPage;
use Plugin\MGD_Barrierefrei\UpdateClient\Factory;

/** Admin-Bereich "Updates": verdrahtet den MGD Update Client mit JTL (Token, Rechte, Version, Domain). */
final class UpdatesTab
{
    // Produktname für die Update-API (Free)
    public const UPDATE_PRODUCT = 'mgd-barrierefrei';
    public const UPDATE_EDITION = 'free';
    public const PLUGIN_NAME = 'MGD Barrierefrei';
    // Prüfsummen-Datei auf All-Inkl (einfacher Modus, solange keine Schlüssel da sind)
    public const SIMPLE_URL = 'https://theme.michael-gahn.de/plugin-updates/mgd-barrierefrei.json';

    /** @var callable(string):void */
    private $sender;

    /** @param (callable(string):void)|null $sender liefert die geprüfte ZIP aus (Test: Fake) */
    public function __construct(private readonly UpdatesPage $page, ?callable $sender = null)
    {
        $this->sender = $sender ?? [self::class, 'sendZip'];
    }

    /** Baut den Bereich aus dem JTL-Plugin; null = Datenordner oder Version nicht nutzbar */
    public static function forShop(object $plugin): ?self
    {
        $dir = UpdateDataDir::forShop();
        $version = self::installedVersion($plugin);
        if ($dir === null || $version === '') {
            return null;
        }
        try {
            $page = Factory::page(
                dirname(__DIR__) . '/update/root-keys.json',
                $dir,
                self::UPDATE_PRODUCT,
                self::UPDATE_EDITION,
                self::PLUGIN_NAME,
                $version,
                (string) parse_url(Shop::getURL(), PHP_URL_HOST),
                defined('APPLICATION_VERSION') ? (string) APPLICATION_VERSION : '',
                static fn (): bool => Form::validateToken(),
                static fn (): bool => self::canManage(),
                Form::getTokenInput(),
                null,
                ['info.xml'],
                self::SIMPLE_URL,
            );
        } catch (\Throwable) {
            return null;
        }

        return new self($page);
    }

    /** @param object $smarty JTLSmarty aus dem Admin @param array<string,mixed> $post */
    public function render(object $smarty, string $method, array $post): string
    {
        try {
            $out = $this->page->handle($method, $post);
        } catch (\Throwable) {
            // keine Detials nach aussen
            $out = ['html' => '<p class="alert alert-danger">Der Update-Bereich hat einen Fehler gemeldet. Der Shop läuft normal weiter.</p>', 'deliver' => null];
        }
        if ($out['deliver'] !== null) {
            ($this->sender)($out['deliver']);

            return '';
        }
        $smarty->assign('mgdUpdates', ['html' => $out['html']]);

        return (string) $smarty->fetch(dirname(__DIR__) . '/adminmenu/templates/updates.tpl');
    }

    // Version aus dem Plugin-Objekt, sonst aus info.xml (nie fest im Code)
    public static function installedVersion(object $plugin): string
    {
        $v = '';
        if (method_exists($plugin, 'getMeta')) {
            $meta = $plugin->getMeta();
            $v = is_object($meta) && method_exists($meta, 'getVersion') ? (string) $meta->getVersion() : '';
        }
        if ($v === '') {
            $xml = @simplexml_load_file(dirname(__DIR__) . '/info.xml');
            $v = $xml !== false ? (string) $xml->Version : '';
        }

        return preg_match('/^\d+\.\d+\.\d+$/', $v) === 1 ? $v : '';
    }

    // Recht im Admin, bei Fehler = kein Zugriff
    private static function canManage(): bool
    {
        try {
            return (bool) Shop::Container()->getAdminAccount()->permission('PLUGIN_ADMIN_VIEW');
        } catch (\Throwable) {
            return false;
        }
    }

    // geprüfte ZIP als Download (nur nach POST + CSRF + Bestätigung, kommt von UpdatesPage)
    public static function sendZip(string $file): void
    {
        if (!is_file($file)) {
            return;
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($file)) . '"');
        header('Content-Length: ' . (string) filesize($file));
        header('X-Content-Type-Options: nosniff');
        readfile($file);
        exit;
    }
}
