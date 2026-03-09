<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class MissingCategoryRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        if (empty(trim($item->category ?? ''))) {
            return [
                'rule'     => 'missing_category',
                'severity' => 'medium',
                'message'  => 'Categorie ontbreekt of is leeg.',
            ];
        }

        return null;
    }
}
