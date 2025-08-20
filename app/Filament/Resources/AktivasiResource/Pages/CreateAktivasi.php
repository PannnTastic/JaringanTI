<?php

namespace App\Filament\Resources\AktivasiResource\Pages;

use App\Filament\Resources\AktivasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAktivasi extends CreateRecord
{
    protected static string $resource = AktivasiResource::class;
    
    protected static ?string $title = 'Tambah Data Aktivasi Substation';
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set user_id ke user yang sedang login saat create
        $data['user_id'] = Auth::id();
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
