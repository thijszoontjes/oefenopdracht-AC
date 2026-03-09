<?php

namespace App\Filament\Resources\CatalogItemResource\Pages;

use App\Filament\Resources\CatalogItemResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewCatalogItem extends ViewRecord
{
    protected static string $resource = CatalogItemResource::class;

    // Filament 5 gebruikt Schema voor zowel forms als infolists
    public function infolist(Schema $infolist): Schema
    {
        return $infolist->components([
            Section::make('Productgegevens')
                ->columns(2)
                ->schema([
                    TextEntry::make('external_id')->label('Extern ID'),
                    TextEntry::make('sku')->label('SKU'),
                    TextEntry::make('title')->label('Titel')->columnSpanFull(),
                    TextEntry::make('description')->label('Omschrijving')->columnSpanFull(),
                    TextEntry::make('category')->label('Categorie'),
                    TextEntry::make('brand')->label('Merk'),
                    TextEntry::make('price')->label('Prijs')->money('EUR'),
                    TextEntry::make('sale_price')->label('Kortingsprijs')->money('EUR'),
                    TextEntry::make('currency')->label('Valuta'),
                    TextEntry::make('stock')->label('Voorraad'),
                    TextEntry::make('availability')->label('Beschikbaarheid'),
                    TextEntry::make('readiness_score')
                        ->label('Readiness score')
                        ->badge()
                        ->color(fn(int $state): string => match (true) {
                            $state >= 80 => 'success',
                            $state >= 50 => 'warning',
                            default      => 'danger',
                        }),
                ]),

            Section::make('Gevonden problemen')
                ->schema([
                    RepeatableEntry::make('issues')
                        ->label('')
                        ->schema([
                            TextEntry::make('severity')
                                ->label('Ernst')
                                ->badge()
                                ->color(fn(string $state): string => match ($state) {
                                    'high'   => 'danger',
                                    'medium' => 'warning',
                                    'low'    => 'info',
                                    default  => 'gray',
                                }),
                            TextEntry::make('message')->label('Omschrijving'),
                        ])
                        ->columns(2),
                ])
                ->hidden(fn($record) => empty($record->issues)),
        ]);
    }
}
