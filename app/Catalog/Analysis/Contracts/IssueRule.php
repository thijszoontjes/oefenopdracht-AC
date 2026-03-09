<?php

namespace App\Catalog\Analysis\Contracts;

use App\Models\CatalogItem;

// Elke issue-regel implementeert deze interface
interface IssueRule
{
    public function check(CatalogItem $item, array $allItems = []): ?array;
}
