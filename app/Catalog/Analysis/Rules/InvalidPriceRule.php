<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class InvalidPriceRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        // Kortingsprijs mag nooit hoger zijn dan de normale prijs
        if ($item->sale_price !== null && $item->sale_price > $item->price) {
            return [
                'rule'     => 'invalid_price',
                'severity' => 'high',
                'message'  => "Kortingsprijs ({$item->sale_price}) is hoger dan normale prijs ({$item->price}).",
            ];
        }

        // Prijs van 0 is ook verdacht
        if ($item->price <= 0) {
            return [
                'rule'     => 'invalid_price',
                'severity' => 'high',
                'message'  => 'Prijs is nul of negatief.',
            ];
        }

        return null;
    }
}
