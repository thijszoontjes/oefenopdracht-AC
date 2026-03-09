<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class NegativeStockRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        if ($item->stock < 0) {
            return [
                'rule'     => 'negative_stock',
                'severity' => 'high',
                'message'  => "Voorraad is negatief ({$item->stock}).",
            ];
        }

        return null;
    }
}
