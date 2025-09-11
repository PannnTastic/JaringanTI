<?php

namespace App\Filament\Resources\SubstationResource\Pages;

use App\Filament\Resources\SubstationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSubstationDocuments extends ViewRecord
{
    protected static string $resource = SubstationResource::class;
    
    protected static string $view = 'filament.resources.substation-resource.pages.view-substation-documents';

    public function getTitle(): string
    {
        return 'Dokumen Substation: ' . ($this->record->substation_name ?? 'Unknown');
    }

    protected function getHeaderActions(): array
    {
        // Cek parameter 'from' untuk menentukan URL kembali yang tepat
        $fromPermit = request()->query('from') === 'permit';
        
        return [
            Actions\Action::make('back')
                ->label($fromPermit ? 'Kembali ke Permit' : 'Kembali ke Substation')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($fromPermit ? 
                    \App\Filament\Resources\PermitResource::getUrl('index') : 
                    static::$resource::getUrl('index')),
            Actions\Action::make('view_substation')
                ->label('Detail Substation')
                ->icon('heroicon-o-building-office-2')
                ->color('info')
                ->url(static::$resource::getUrl('edit', ['record' => $this->record]))
                ->visible(fn() => $fromPermit), // Hanya tampil jika dari permit
            Actions\EditAction::make()
                ->label('Edit Substation'),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Eager load documents dengan relasi
        $this->record->load(['documents.user', 'user', 'pops']);
    }
}
