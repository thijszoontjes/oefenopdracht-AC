<?php

namespace App\Filament\Resources\CatalogItemResource\Pages;

use App\Filament\Resources\CatalogItemResource;
use App\Filament\Resources\CatalogItemResource\Widgets\CatalogHealthStats;
use Filament\Resources\Pages\ListRecords;

class ListCatalogItems extends ListRecords
{
    protected static string $resource = CatalogItemResource::class;

    // Widgets boven de tabel tonen
    protected function getHeaderWidgets(): array
    {
        return [
            CatalogHealthStats::class,
        ];
    }
}
