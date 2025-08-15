<?php

namespace App\Filament\Resources\AktivasiResource\Pages;

use App\Filament\Resources\AktivasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAktivasi extends CreateRecord
{
    protected static string $resource = AktivasiResource::class;
    
    protected static ?string $title = 'Tambah Data Aktivasi Substation';
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
