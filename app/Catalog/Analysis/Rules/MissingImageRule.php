<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class MissingImageRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        $heeftThumbnail = !empty($item->thumbnail_url);

        // Kijk ook in de ruwe payload of er een bruikbare main image is
        $images = $item->raw_payload['images'] ?? [];
        $heeftMainImage = collect($images)
            ->filter(fn($img) => !empty($img['url']) && ($img['type'] ?? '') === 'main')
            ->isNotEmpty();

        if (!$heeftThumbnail && !$heeftMainImage) {
            return [
                'rule'     => 'missing_image',
                'severity' => 'high',
                'message'  => 'Geen bruikbare thumbnail of hoofdafbeelding gevonden.',
            ];
        }

        return null;
    }
}
