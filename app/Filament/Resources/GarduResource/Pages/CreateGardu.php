<?php

namespace App\Filament\Resources\GarduResource\Pages;

use App\Filament\Resources\GarduResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGardu extends CreateRecord
{
    protected static string $resource = GarduResource::class;
        protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
