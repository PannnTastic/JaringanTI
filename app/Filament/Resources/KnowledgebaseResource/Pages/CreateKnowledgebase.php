<?php

namespace App\Filament\Resources\KnowledgebaseResource\Pages;

use App\Filament\Resources\KnowledgebaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKnowledgebase extends CreateRecord
{
    protected static string $resource = KnowledgebaseResource::class;
            protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
