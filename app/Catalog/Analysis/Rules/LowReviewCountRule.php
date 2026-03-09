<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

/**
 * Eigen regel: producten met te weinig reviews zijn minder betrouwbaar.
 * Dit is relevant voor AI-verrijking (lage datakwaliteit) en conversie.
 * Weinig reviews betekent ook dat de rating statistisch onbetrouwbaar is.
 */
class LowReviewCountRule implements IssueRule
{
    private int $minimum = 5;

    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        if ($item->review_count < $this->minimum) {
            return [
                'rule'     => 'low_review_count',
                'severity' => 'low',
                'message'  => "Slechts {$item->review_count} review(s) — te weinig voor betrouwbare beoordeling.",
            ];
        }

        return null;
    }
}
