<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected static string $view = 'filament.resources.document-resource.pages.view-document';
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    if (!$this->record->doc_file) {
                        \Filament\Notifications\Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();
                        return;
                    }

                    $filePath = storage_path('app/public/' . $this->record->doc_file);
                    
                    if (!file_exists($filePath)) {
                        \Filament\Notifications\Notification::make()
                            ->title('File tidak ada di server')
                            ->danger()
                            ->send();
                        return;
                    }

                    return response()->download($filePath, $this->record->doc_name . '.' . pathinfo($this->record->doc_file, PATHINFO_EXTENSION));
                })
                ->visible(fn () => !empty($this->record->doc_file)),
                
            Actions\EditAction::make(),
        ];
    }
}
