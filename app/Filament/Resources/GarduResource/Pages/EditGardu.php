<?php

namespace App\Filament\Resources\GarduResource\Pages;

use App\Filament\Resources\GarduResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGardu extends EditRecord
{
    protected static string $resource = GarduResource::class;

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
