<?php

namespace App\Filament\Resources\PopResource\Pages;

use App\Filament\Resources\PopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPop extends EditRecord
{
    protected static string $resource = PopResource::class;

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
