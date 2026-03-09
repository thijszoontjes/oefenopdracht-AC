<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

class TitleLengthRule implements IssueRule
{
    private int $min = 8;
    private int $max = 70;

    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        $lengte = strlen($item->title ?? '');

        if ($lengte < $this->min || $lengte > $this->max) {
            return [
                'rule'     => 'title_length',
                'severity' => 'low',
                'message'  => "Titel heeft {$lengte} tekens (verwacht: {$this->min}–{$this->max}).",
            ];
        }

        return null;
    }
}
