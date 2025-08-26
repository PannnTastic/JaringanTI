<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Vendor;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LatestActivity extends Widget
{
    protected static string $view = 'filament.widgets.latest-activity';
    
    protected static ?int $sort = 9;
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $users = DB::table('users')
            ->select([
                'name as title',
                'email as description',
                'created_at',
                'updated_at',
                DB::raw("'User' as type")
            ])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                $lastModified = $user->updated_at ?? $user->created_at;
                return (object)[
                    'title' => $user->title,
                    'description' => $user->description,
                    'type' => $user->type,
                    'last_modified' => $lastModified,
                    'keterangan' => $user->created_at === $user->updated_at
                        ? '✨ Akun baru dibuat'
                        : '🔄 Mengubah informasi profil'
                ];
            });

        $vendors = DB::table('vendors')
            ->select([
                'vendor_name as title',
                'vendor_year as description',
                'created_at',
                'updated_at',
                DB::raw("'Vendor' as type")
            ])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($vendor) {
                $lastModified = $vendor->updated_at ?? $vendor->created_at;
                return (object)[
                    'title' => $vendor->title,
                    'description' => $vendor->description,
                    'type' => $vendor->type,
                    'last_modified' => $lastModified,
                    'keterangan' => $vendor->created_at === $vendor->updated_at
                        ? '🏢 Vendor baru ditambahkan'
                        : '📝 Memperbarui data vendor'
                ];
            });

        $activities = $users->concat($vendors)
            ->sortByDesc('last_modified')
            ->take(10);

        return [
            'activities' => $activities
        ];


    }
}