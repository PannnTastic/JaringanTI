<?php

namespace App\Filament\Resources\PenyerapanResource\Pages;

use App\Filament\Resources\PenyerapanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenyerapan extends EditRecord
{
    protected static string $resource = PenyerapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
