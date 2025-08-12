<?php

namespace App\Filament\Resources\PopResource\Pages;

use App\Filament\Resources\PopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPops extends ListRecords
{
    protected static string $resource = PopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
