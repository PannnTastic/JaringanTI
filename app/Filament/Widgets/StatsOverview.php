<?php


namespace App\Filament\Widgets;

use App\Models\User;
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

    // Harus return int (sesuai parent), jangan ubah ke union.
    protected function getColumns(): int
    {
        return 3; // grid 4 kolom (xl)
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Pengguna')
                ->color('success')
                ->extraAttributes([
                    // span 2 kolom di md ke atas
                    'class' => 'md:col-span-2',
                ]),

            Stat::make('Total Vendors', Vendor::count())
                ->description('Vendor')
                ->color('info')
                ->extraAttributes([
                    // span 2 kolom di md ke atas
                    'class' => 'md:col-span-1',
                ]),

            Stat::make('Total Budget', Budget::count())
                ->description('Budget')
                ->color('warning')
                ->extraAttributes([
                    // span 3 kolom di md ke atas
                    'class' => 'md:col-span-1',
                ]),

            Stat::make('Total Substation', Substation::count())
                ->description('Substation')
                ->color('primary')
                ->extraAttributes([
                    // span 2 kolom di md ke atas
                    'class' => 'md:col-span-1',
                ]),

            Stat::make('Total POP', Pop::count())
                ->description('POP')
                ->color('secondary')
                ->extraAttributes([
                    'class' => 'md:col-span-1',
                ]),
        ];
    }
}