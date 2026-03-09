<?php

namespace App\Catalog\Analysis\Contracts;

use App\Models\CatalogItem;

interface IssueRule
{
    /**
     * Controleer één item op een specifiek probleem.
     *
     * Geeft null terug als er geen probleem is.
     * Geeft een array terug met: rule, severity (high/medium/low) en message.
     *
     * @param  CatalogItem[]  $allItems  Alle items in de batch (voor cross-item checks)
     */
    public function check(CatalogItem $item, array $allItems = []): ?array;
}
