<?php

namespace App\Filament\Resources\AktivasiResource\Pages;

use App\Filament\Resources\AktivasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAktivasi extends ViewRecord
{
    protected static string $resource = AktivasiResource::class;
    
    protected static ?string $title = 'Detail Aktivasi Substation';
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit')
                ->icon('heroicon-s-pencil'),
        ];
    }
}
