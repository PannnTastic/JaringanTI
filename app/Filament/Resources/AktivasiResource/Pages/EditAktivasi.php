<?php

namespace App\Filament\Resources\AktivasiResource\Pages;

use App\Filament\Resources\AktivasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditAktivasi extends EditRecord
{
    protected static string $resource = AktivasiResource::class;
    
    protected static ?string $title = 'Aktivasi Substation';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Lihat Detail'),
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->icon('heroicon-s-trash'),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Set user_id ke user yang sedang login saat edit
        $data['user_id'] = Auth::id();
        
        return $data;
    }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Substation berhasil diaktivasi!';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
