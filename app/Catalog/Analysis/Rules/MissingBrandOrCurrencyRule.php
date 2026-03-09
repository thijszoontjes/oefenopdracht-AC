<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class MissingBrandOrCurrencyRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        $ontbrekend = [];

        if (empty($item->brand)) {
            $ontbrekend[] = 'merk (brand)';
        }

        if (empty($item->currency)) {
            $ontbrekend[] = 'valuta (currency)';
        }

        if (!empty($ontbrekend)) {
            return [
                'rule'     => 'missing_brand_or_currency',
                'severity' => 'medium',
                'message'  => 'Ontbrekend veld: ' . implode(', ', $ontbrekend) . '.',
            ];
        }

        return null;
    }
}
