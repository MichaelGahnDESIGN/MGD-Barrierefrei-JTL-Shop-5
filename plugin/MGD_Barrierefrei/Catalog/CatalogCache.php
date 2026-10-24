<?php

declare(strict_types=1);

namespace Plugin\MGD_Barrierefrei\Catalog;

/** Kleiner Speicher für den Feed, damit man ihn ohne JTL-Shop testen kann. */
interface CatalogCache
{
    /** @return array<string, mixed>|null */
    public function get(): ?array;

    /** @param array<string, mixed> $record */
    public function set(array $record, int $ttlSeconds): void;
}
