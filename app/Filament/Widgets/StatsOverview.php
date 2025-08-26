<?php


namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Field;
use App\Models\Vendor;
use App\Models\Budget;
use App\Models\Gardu;
use App\Models\Pop;
use App\Models\Substation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;

    // Harus return int (sesuai parent), jangan ubah ke union.
    protected function getColumns(): int
    {
        return 3; // grid 4 kolom (xl)
    }

    protected function getStats(): array
    {
        // Get all roles and their counts
        $field = Field::all();
        $stats = [];

        // Add total users stat
        $stats[] = Stat::make('Total Users', User::count())
            ->description('Semua Pengguna')
            ->icon('heroicon-o-users')
            ->color('success')
            ->extraAttributes([
                'class' => 'md:col-span-2 xl:col-span-2',
            ]);

        // Add stats for each role
        // foreach ($field as $field) {
        //     $userCount = User::where('field_id', $field->field_id)->count();
        //     $stats[] = Stat::make($field->field_name, $userCount)
        //         ->description('Pengguna')
        //         ->icon('heroicon-o-user-group')
        //         ->color($this->getRoleColor($field->field_name))
        //         ->extraAttributes([
        //             'class' => 'md:col-span-1 xl:col-span-1',
        //         ]);
        // }

        // Add other stats
        $stats[] = Stat::make('Total Vendors', Vendor::count())
            ->description('Vendor')
            ->icon('heroicon-o-building-office')
            ->color('info')
            ->extraAttributes([
                'class' => 'md:col-span-1',
            ]);

        // $stats[] = Stat::make('Total Budget', Budget::count())
        //     ->description('Budget')
        //     ->icon('heroicon-o-currency-dollar')
        //     ->color('warning')
        //     ->extraAttributes([
        //         'class' => 'md:col-span-1',
        //     ]);

        $stats[] = Stat::make('Total Substation', Substation::count())
            ->description('Substation')
            ->icon('heroicon-o-bolt')
            ->color('primary')
            ->extraAttributes([
                'class' => 'md:col-span-2',
            ]);

        $stats[] = Stat::make('Total POP', Pop::count())
            ->description('POP')
            ->icon('heroicon-o-signal')
            ->color('secondary')
            ->extraAttributes([
                'class' => 'md:col-span-1',
            ]);

        return $stats;
    }

    protected function getRoleColor(string $roleName): string
    {
        return match (strtolower($roleName)) {
            'admin' => 'danger',
            'manager' => 'warning',
            'supervisor' => 'info',
            default => 'primary',
        };
    }
    
    
}