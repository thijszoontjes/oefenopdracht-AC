<?php

namespace App\Filament\Resources\CatalogItemResource\Widgets;

use App\Models\CatalogItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CatalogHealthStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totaal         = CatalogItem::count();
        $metProblemen   = CatalogItem::where('issue_count', '>', 0)->count();
        $kritiek        = CatalogItem::whereJsonContains('issues', ['severity' => 'high'])->count();
        $gemiddeldeScore = $totaal > 0
            ? (int) CatalogItem::avg('readiness_score')
            : 0;
        $klaarVoorGebruik = CatalogItem::where('issue_count', 0)->count();

        return [
            Stat::make('Totaal producten', $totaal)
                ->description('Geïmporteerd uit de feed')
                ->icon('heroicon-o-cube'),

            Stat::make('Heeft problemen', $metProblemen)
                ->description("{$metProblemen} van {$totaal} producten")
                ->color($metProblemen > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Kritieke problemen', $kritiek)
                ->description('Hoge prioriteit — direct actie vereist')
                ->color($kritiek > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-x-circle'),

            Stat::make('Gemiddelde score', $gemiddeldeScore . ' / 100')
                ->description('Feed-breed gemiddeld')
                ->color(match (true) {
                    $gemiddeldeScore >= 80 => 'success',
                    $gemiddeldeScore >= 50 => 'warning',
                    default                => 'danger',
                })
                ->icon('heroicon-o-chart-bar'),

            // Eigen KPI: hoeveel producten zijn écht klaar voor gebruik
            Stat::make('Klaar voor gebruik', $klaarVoorGebruik)
                ->description('Geen enkel probleem gevonden')
                ->color($klaarVoorGebruik === $totaal ? 'success' : 'info')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
