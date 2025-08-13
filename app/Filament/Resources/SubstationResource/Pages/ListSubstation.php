<?php

namespace App\Filament\Resources\SubstationResource\Pages;

use App\Filament\Resources\SubstationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubstation extends ListRecords
{
    protected static string $resource = SubstationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
