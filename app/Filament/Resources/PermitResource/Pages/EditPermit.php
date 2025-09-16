<?php

namespace App\Filament\Resources\PermitResource\Pages;

use App\Filament\Resources\PermitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermit extends EditRecord
{
    protected static string $resource = PermitResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load current approvers untuk ditampilkan di form
        $data['approver_roles'] = $this->record->approvers()->pluck('role_id')->toArray();
        
        return $data;
    }

    protected function afterSave(): void
    {
        $newRoles = $this->data['approver_roles'] ?? [];
        
        // Ambil data approver yang sudah ada sebelumnya
        $currentApprovers = $this->record->approvers()->withPivot(['approver_status', 'approved_at'])->get();
        
        // Hapus semua approver yang lama
        $this->record->approvers()->detach();
        
        // Attach roles baru dengan mempertahankan status yang sudah ada jika role sama
        foreach ($newRoles as $roleId) {
            $existingApprover = $currentApprovers->where('role_id', $roleId)->first();
            
            if ($existingApprover) {
                // Role sudah ada sebelumnya, pertahankan status yang sudah ada
                $this->record->approvers()->attach($roleId, [
                    'approver_status' => $existingApprover->pivot->approver_status,
                    'approved_at' => $existingApprover->pivot->approved_at
                ]);
            } else {
                // Role baru, buat dengan status awal 0 (belum disetujui)
                $this->record->approvers()->attach($roleId, [
                    'approver_status' => 0,
                    'approved_at' => null
                ]);
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman index dengan filter untuk menampilkan record yang baru diedit
        return $this->getResource()::getUrl('index') . '?tableSearch=' . $this->record->permit_id;
    }
}
