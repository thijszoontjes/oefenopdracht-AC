<?php

namespace App\Catalog\Import;

use App\Catalog\Analysis\CatalogHealthAnalyzer;
use App\Models\CatalogItem;
use Carbon\Carbon;

class CatalogImporter
{
    public function __construct(
        private readonly CatalogFeedClient     $client,
        private readonly CatalogHealthAnalyzer $analyzer,
    ) {}

    /**
     * Haalt de feed op, mapt naar CatalogItem, analyseert en slaat op.
     * Bestaande items worden bijgewerkt (op basis van external_id).
     *
     * @return int  Aantal verwerkte items
     */
    public function importeer(): int
    {
        $producten = $this->client->laad();

        // Eerst alles mappen zodat de duplicate-SKU-regel de volledige batch ziet
        $items = array_map(fn($product) => $this->mapNaarItem($product), $producten);

        foreach ($items as $item) {
            $this->analyzer->analyze($item, $items);
            $item->save();
        }

        return count($items);
    }

    /**
     * Vertaalt één rij uit de bronfeed naar een CatalogItem.
     * Onbekende velden worden bewaard in raw_payload.
     */
    private function mapNaarItem(array $product): CatalogItem
    {
        // Ondersteuning voor zowel fixture-formaat als DummyJSON-formaat
        $externalId = (string) ($product['external_id'] ?? $product['id'] ?? '');

        $item = CatalogItem::firstOrNew(['external_id' => $externalId]);

        $item->fill([
            'external_id'       => $externalId,
            'sku'               => $product['sku'] ?? '',
            'title'             => $product['title'] ?? '',
            'description'       => $product['description'] ?? '',
            'category'          => $product['category'] ?? '',
            'brand'             => $product['brand'] ?? null,
            'price'             => (float) ($product['price'] ?? 0),
            'sale_price'        => isset($product['sale_price']) ? (float) $product['sale_price'] : null,
            'currency'          => $product['currency'] ?? null,
            'stock'             => (int) ($product['stock'] ?? 0),
            'availability'      => $product['availability'] ?? null,
            'thumbnail_url'     => $product['thumbnail_url'] ?? $product['thumbnail'] ?? null,
            'source_url'        => $product['source_url'] ?? null,
            'rating'            => isset($product['rating']) ? (float) $product['rating'] : null,
            'review_count'      => (int) ($product['review_count'] ?? $product['reviews'] ?? 0),
            'raw_payload'       => $product,
            'updated_at_source' => isset($product['updated_at'])
                ? Carbon::parse($product['updated_at'])
                : null,
        ]);

        return $item;
    }
}
