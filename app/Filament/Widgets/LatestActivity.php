<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Vendor;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class LatestActivity extends Widget
{
    protected static string $view = 'filament.widgets.latest-activity';
    
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $users = User::select('name as title', 'email as description', 'created_at')
            ->addSelect(DB::raw('"User" as type'))
            ->latest()
            ->take(5)
            ->get();

        $vendors = Vendor::select('vendor_name as title', 'vendor_year as description', 'created_at')
            ->addSelect(DB::raw('"Vendor" as type'))
            ->latest()
            ->take(5)
            ->get();

        $activities = $users->concat($vendors)
            ->sortByDesc('created_at')
            ->take(10);

        return [
            'activities' => $activities
        ];
    }
}