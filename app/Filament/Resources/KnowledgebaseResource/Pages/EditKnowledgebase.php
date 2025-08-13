<?php

namespace App\Filament\Resources\KnowledgebaseResource\Pages;

use App\Filament\Resources\KnowledgebaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKnowledgebase extends EditRecord
{
    protected static string $resource = KnowledgebaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
        protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
