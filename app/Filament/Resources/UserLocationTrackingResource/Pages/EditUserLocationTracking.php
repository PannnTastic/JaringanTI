<?php

namespace App\Filament\Resources\UserLocationTrackingResource\Pages;

use App\Filament\Resources\UserLocationTrackingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserLocationTracking extends EditRecord
{
    protected static string $resource = UserLocationTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
