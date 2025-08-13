<?php

namespace App\Filament\Resources\KnowledgebaseResource\Pages;

use App\Filament\Resources\KnowledgebaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKnowledgebases extends ListRecords
{
    protected static string $resource = KnowledgebaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
