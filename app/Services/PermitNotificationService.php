<?php

namespace App\Services;

use App\Models\Permit;
use App\Models\User;
use App\Notifications\PermitStatusNotification;
use Illuminate\Support\Facades\Log;

class PermitNotificationService
{
    /**
     * Kirim notifikasi ke approver berikutnya setelah approval
     */
    public static function notifyNextApprover(Permit $permit): void
    {
        $nextRole = \App\Filament\Resources\PermitResource::getNextApprovalRole($permit);
        
        if (!$nextRole) {
            return; // Tidak ada approver berikutnya
        }
        
        // Cari users dengan role tersebut
        $nextApprovers = User::whereHas('role', function ($query) use ($nextRole) {
            $query->whereRaw('LOWER(role_name) = ?', [strtolower($nextRole)]);
        })->get();
        
        foreach ($nextApprovers as $approver) {
            try {
                $title = 'Permit Menunggu Approval Anda';
                $body = "Permit ID #{$permit->permit_id} dari {$permit->users->name} menunggu persetujuan Anda.";
                
                $approver->notify(new PermitStatusNotification($permit, $title, $body, 'info'));
                    
            } catch (\Exception $e) {
                Log::error('Failed to send notification to next approver', [
                    'permit_id' => $permit->permit_id,
                    'approver_id' => $approver->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Kirim notifikasi ke pembuat permit setelah approval penuh
     */
    public static function notifyPermitCreator(Permit $permit, string $status = 'approved', ?string $rejectionReason = null): void
    {
        $creator = $permit->users;
        
        if (!$creator) {
            return;
        }
        
        try {
            $title = match($status) {
                'approved' => 'Permit Disetujui',
                'rejected' => 'Permit Ditolak',
                default => 'Status Permit Berubah'
            };
            
            $body = match($status) {
                'approved' => "Permit ID #{$permit->permit_id} Anda telah disetujui oleh semua approver.",
                'rejected' => "Permit ID #{$permit->permit_id} Anda telah ditolak oleh {$permit->rejected_by}." . 
                             ($rejectionReason ? "\n\nAlasan: {$rejectionReason}" : ''),
                default => "Status permit ID #{$permit->permit_id} Anda telah berubah."
            };
            
            $type = match($status) {
                'approved' => 'success',
                'rejected' => 'danger',
                default => 'info'
            };
            
            $creator->notify(new PermitStatusNotification($permit, $title, $body, $type, $rejectionReason));
                
        } catch (\Exception $e) {
            Log::error('Failed to send notification to permit creator', [
                'permit_id' => $permit->permit_id,
                'creator_id' => $creator->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Kirim notifikasi ke approvers ketika permit direset
     */
    public static function notifyApproversOnReset(Permit $permit): void
    {
        // Cari semua approvers untuk permit ini
        $approvers = $permit->approvers;
        
        foreach ($approvers as $approver) {
            try {
                $title = 'Permit Direset';
                $body = "Permit ID #{$permit->permit_id} dari {$permit->users->name} telah direset dan memerlukan approval ulang.";
                
                $approver->notify(new PermitStatusNotification($permit, $title, $body, 'warning'));
                    
            } catch (\Exception $e) {
                Log::error('Failed to send reset notification to approver', [
                    'permit_id' => $permit->permit_id,
                    'approver_id' => $approver->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
