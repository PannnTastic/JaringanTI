<?php

namespace App\Filament\Resources\SubstationResource\Pages;

use App\Filament\Resources\SubstationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubstation extends CreateRecord
{
    protected static string $resource = SubstationResource::class;
        protected function getRedirectUrl(): string{
        return $this->getResource()::getUrl('index');
    }
}
