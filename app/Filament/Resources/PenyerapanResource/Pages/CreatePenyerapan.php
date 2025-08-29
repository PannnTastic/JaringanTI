<?php

namespace App\Filament\Resources\PenyerapanResource\Pages;

use App\Filament\Resources\PenyerapanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePenyerapan extends CreateRecord
{
    protected static string $resource = PenyerapanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
