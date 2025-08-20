<?php

namespace App\Filament\Resources\AktivasiResource\Pages;

use App\Filament\Resources\AktivasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAktivasis extends ListRecords
{
    protected static string $resource = AktivasiResource::class;
    
    protected static ?string $title = 'Data Aktivasi Substation';

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
