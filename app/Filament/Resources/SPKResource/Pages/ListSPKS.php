<?php

namespace App\Filament\Resources\SPKResource\Pages;

use App\Filament\Resources\SPKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSPKS extends ListRecords
{
    protected static string $resource = SPKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
