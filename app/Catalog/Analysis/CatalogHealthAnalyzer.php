<?php

namespace App\Catalog\Analysis;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Catalog\Analysis\Rules\DuplicateSkuRule;
use App\Catalog\Analysis\Rules\InvalidPriceRule;
use App\Catalog\Analysis\Rules\LowReviewCountRule;
use App\Catalog\Analysis\Rules\MissingBrandOrCurrencyRule;
use App\Catalog\Analysis\Rules\MissingCategoryRule;
use App\Catalog\Analysis\Rules\MissingImageRule;
use App\Catalog\Analysis\Rules\NegativeStockRule;
use App\Catalog\Analysis\Rules\TitleLengthRule;
use App\Catalog\Analysis\Rules\WeakDescriptionRule;
use App\Models\CatalogItem;

class CatalogHealthAnalyzer
{
    /** @var IssueRule[] */
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function analyze(CatalogItem $item, array $allItems = []): void
    {
        $issues = [];

        foreach ($this->rules as $rule) {
            $issue = $rule->check($item, $allItems);

            if ($issue !== null) {
                $issues[] = $issue;
            }
        }

        $item->issues          = $issues;
        $item->issue_count     = count($issues);
        $item->readiness_score = $this->berekenScore($issues);
    }

    // Score: begin op 100, trek af per ernst (hoog −20, midden −10, laag −5)
    private function berekenScore(array $issues): int
    {
        $score = 100;

        foreach ($issues as $issue) {
            $score -= match ($issue['severity'] ?? '') {
                'high'   => 20,
                'medium' => 10,
                'low'    => 5,
                default  => 0,
            };
        }

        return max(0, $score);
    }

    public static function standaard(): self
    {
        return new self([
            new DuplicateSkuRule(),
            new MissingImageRule(),
            new WeakDescriptionRule(),
            new InvalidPriceRule(),
            new NegativeStockRule(),
            new MissingCategoryRule(),
            new TitleLengthRule(),
            new MissingBrandOrCurrencyRule(),
            new LowReviewCountRule(),
        ]);
    }
}
