<?php

namespace App\Catalog\Analysis\Rules;

use App\Catalog\Analysis\Contracts\IssueRule;
use App\Models\CatalogItem;

// Eigen regel: spam-achtige titels zijn slecht voor SEO en UX en komen veel voor in slechte feeds
class SpamTitleRule implements IssueRule
{
    private array $spamWoorden = ['best', 'cheap', 'free', 'sale', 'buy now', 'limited', 'discount'];

    public function check(CatalogItem $item, array $allItems = []): ?array
    {
        $titel = $item->title ?? '';

        // Te veel hoofdletters: meer dan 50% van de letters is uppercase
        $letters = preg_replace('/[^a-zA-Z]/', '', $titel);
        if (strlen($letters) > 4 && (strlen(preg_replace('/[^A-Z]/', '', $letters)) / strlen($letters)) > 0.5) {
            return [
                'rule'     => 'spam_title',
                'severity' => 'medium',
                'message'  => 'Titel bevat te veel hoofdletters — lijkt op spam of slechte feed.',
            ];
        }

        // Bevat spam-woorden (hoofdletterongevoelig)
        $titelLower = strtolower($titel);
        foreach ($this->spamWoorden as $woord) {
            if (str_contains($titelLower, $woord)) {
                return [
                    'rule'     => 'spam_title',
                    'severity' => 'medium',
                    'message'  => "Titel bevat spam-achtig woord: \"{$woord}\".",
                ];
            }
        }

        return null;
    }
}
