<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Presentation;

use Plugin\MGD_Barrierefrei\Catalog\FeedNormalizer;
use Plugin\MGD_Barrierefrei\Catalog\FileCatalogCache;
use Plugin\MGD_Barrierefrei\Catalog\PluginCatalog;

/** Admin-Tab "Weitere Plugins": holt die Liste und gibt das Smarty-Template aus. */
final class CatalogTab
{
    public const TAB_NAME = 'Weitere Plugins';
    public const OWN_ID = 'plugin-barrierefrei-jtl';

    // pro Aufruf nur einmal ausgeben (falls JTL Datei und Bootstrap beide nutzt)
    private static bool $rendered = false;

    public function __construct(private readonly ?PluginCatalog $catalog = null)
    {
    }

    /** @param object $smarty  JTLSmarty aus dem Admin */
    public function render(object $smarty): string
    {
        if (self::$rendered) {
            return '';
        }
        self::$rendered = true;

        $catalog = $this->catalog ?? new PluginCatalog(
            FileCatalogCache::forShop(),
            new FeedNormalizer(['jtl', 'jtl5'], self::OWN_ID),
        );
        $result = $catalog->load($this->refreshRequested());
        $fetchedAt = $result['fetchedAt'] ?? null;
        // Anfangsbuchstabe als Ersatz-Icon
        $items = array_map(
            static fn (array $item): array => $item + ['initial' => mb_strtoupper(mb_substr((string) $item['name'], 0, 1))],
            $result['items'] ?? [],
        );

        $smarty->assign('mgdCatalog', [
            'available' => $result !== null,
            'stale' => $result['stale'] ?? false,
            'items' => $items,
            'hasItems' => $items !== [],
            'updatedAt' => is_int($fetchedAt) && $fetchedAt > 0 ? date('d.m.Y H:i', $fetchedAt) : '',
            'tokenInput' => $this->tokenInput(),
        ]);

        return (string) $smarty->fetch(dirname(__DIR__) . '/adminmenu/templates/weitere-plugins.tpl');
    }

    // Aktualisieren nur per POST mit gültigem JTL-Token
    private function refreshRequested(): bool
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !isset($_POST['mgd_catalog_refresh'])) {
            return false;
        }
        $form = '\JTL\Helpers\Form';

        return class_exists($form) && $form::validateToken();
    }

    private function tokenInput(): string
    {
        $form = '\JTL\Helpers\Form';

        return class_exists($form) && method_exists($form, 'getTokenInput') ? (string) $form::getTokenInput() : '';
    }
}
