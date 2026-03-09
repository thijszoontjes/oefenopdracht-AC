<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class DuplicateSkuRule implements IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        $aantalMetZelfdesku = collect($allItems)
            ->where('sku', $item->sku)
            ->count();

        if ($aantalMetZelfdesku > 1) {
            return [
                'rule'     => 'duplicate_sku',
                'severity' => 'high',
                'message'  => "SKU '{$item->sku}' komt {$aantalMetZelfdesku} keer voor in de feed.",
            ];
        }

        return null;
    }
}
