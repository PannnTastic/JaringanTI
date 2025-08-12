<?php

namespace App\Filament\Resources\PopResource\Pages;

use App\Filament\Resources\PopResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePop extends CreateRecord
{
    protected static string $resource = PopResource::class;

        protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}

