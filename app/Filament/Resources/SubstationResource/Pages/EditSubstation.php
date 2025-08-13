<?php

namespace App\Filament\Resources\SubstationResource\Pages;

use App\Filament\Resources\SubstationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubstation extends EditRecord
{
    protected static string $resource = SubstationResource::class;

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
