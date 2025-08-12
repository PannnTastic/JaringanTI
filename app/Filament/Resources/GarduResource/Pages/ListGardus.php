<?php

namespace App\Filament\Resources\GarduResource\Pages;

use App\Filament\Resources\GarduResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGardus extends ListRecords
{
    protected static string $resource = GarduResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
