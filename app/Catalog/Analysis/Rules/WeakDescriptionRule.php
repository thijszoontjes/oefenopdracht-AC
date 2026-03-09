<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class WeakDescriptionRule implements IssueRule
{
    private int $minimumLengte = 80;

    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        if (strlen(trim($item->description ?? '')) < $this->minimumLengte) {
            return [
                'rule'     => 'weak_description',
                'severity' => 'medium',
                'message'  => "Omschrijving is leeg of korter dan {$this->minimumLengte} tekens.",
            ];
        }

        return null;
    }
}
