<?php

namespace App\Filament\Resources\PermitResource\Pages;

use Filament\Actions;
use App\Filament\Resources\PermitResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermit extends CreateRecord
{
    protected static string $resource = PermitResource::class;

    protected function afterCreate(): void
    {
        $roles = $this->data['roles'] ?? [];

        // Attach roles to permit dengan status awal 0 (belum disetujui)
        foreach ($roles as $roleId) {
            $this->record->approvers()->attach($roleId, [
                'approver_status' => 0,
                'approved_at' => null
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
}
