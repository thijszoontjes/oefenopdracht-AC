<?php

namespace App\Catalog\Import;

use Illuminate\Support\Facades\Http;

class CatalogFeedClient
{
    /**
     * Laadt de productfeed.
     * Lokale fixture heeft prioriteit (geen netwerkafhankelijkheid bij demo).
     * Fallback: DummyJSON API.
     */
    public function laad(): array
    {
        $fixturePad = database_path('fixtures/catalog-health-feed-curated-final.json');

        if (file_exists($fixturePad)) {
            $data = json_decode(file_get_contents($fixturePad), true);
            return $data['products'] ?? [];
        }

        $response = Http::timeout(10)->get('https://dummyjson.com/products?limit=30');

        return $response->json('products', []);
    }
}
