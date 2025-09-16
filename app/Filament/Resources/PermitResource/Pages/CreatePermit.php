<?php

namespace App\Filament\Resources\PermitResource\Pages;

use Filament\Actions;
use App\Filament\Resources\PermitResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\MailNotification;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Mail;


class CreatePermit extends CreateRecord
{
    protected static string $resource = PermitResource::class;

    protected function afterCreate(): void
    {
        $roles = $this->data['approver_roles'] ?? [];

    $email = Auth()->user()->email;
    // Attach roles to permit dengan status awal 0 (belum disetujui)
    Mail::to($email)->send(new MailNotification());
        
   
        foreach ($roles as $roleId) {
            $this->record->approvers()->attach($roleId, [
                'approver_status' => 0,
                'approved_at' => null
            ]);
        }
    }


    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman index dengan filter untuk menampilkan record yang baru dibuat
        return $this->getResource()::getUrl('index') . '?tableSearch=' . $this->record->permit_id;
    }

    
}
