<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogItemResource\Pages;
use App\Filament\Resources\CatalogItemResource\Widgets\CatalogHealthStats;
use App\Models\CatalogItem;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class CatalogItemResource extends Resource
{
    protected static ?string $model = CatalogItem::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Catalogus';
    protected static ?string $modelLabel = 'Product';
    protected static ?string $pluralModelLabel = 'Producten';

    public static function form(Schema $form): Schema
    {
        // Alleen-lezen: producten worden ingeladen via catalog:import
        return $form->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('')
                    ->width(56)
                    ->height(56)
                    ->defaultImageUrl('https://placehold.co/56x56?text=?'),

                TextColumn::make('external_id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Titel')
                    ->sortable()
                    ->searchable()
                    ->limit(50),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Categorie')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Prijs')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('readiness_score')
                    ->label('Score')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 50 => 'warning',
                        default      => 'danger',
                    }),

                TextColumn::make('issue_count')
                    ->label('Problemen')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'danger' : 'success'),

                TextColumn::make('availability')
                    ->label('Beschikbaarheid')
                    ->badge(),
            ])
            ->defaultSort('readiness_score', 'asc') // slechtste bovenaan
            ->filters([
                Filter::make('met_problemen')
                    ->label('Heeft problemen')
                    ->query(fn(Builder $query) => $query->where('issue_count', '>', 0)),

                SelectFilter::make('availability')
                    ->label('Beschikbaarheid')
                    ->options([
                        'in_stock'  => 'Op voorraad',
                        'low_stock' => 'Lage voorraad',
                        'preorder'  => 'Pre-order',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            CatalogHealthStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogItems::route('/'),
            'view'  => Pages\ViewCatalogItem::route('/{record}'),
        ];
    }
}
