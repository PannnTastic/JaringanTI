<?php

namespace App\Filament\Resources\PenyerapanResource\Pages;

use App\Filament\Resources\PenyerapanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenyerapans extends ListRecords
{
    protected static string $resource = PenyerapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
