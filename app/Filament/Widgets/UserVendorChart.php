<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Budget;
use App\Models\Gardu;
use App\Models\Pop;
use Filament\Widgets\ChartWidget;

class UserVendorChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Data';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Data',
                    'data' => [
                        User::count(),
                        Vendor::count(),
                        Budget::count(),
                        Gardu::count(),
                        Pop::count(),
                    ],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',  // Blue
                        'rgb(16, 185, 129)',  // Green
                        'rgb(245, 158, 11)',  // Yellow
                        'rgb(239, 68, 68)',   // Red
                        'rgb(139, 92, 246)',  // Purple
                    ],
                    'borderColor' => [
                        'rgb(29, 78, 216)',
                        'rgb(5, 150, 105)',
                        'rgb(217, 119, 6)',
                        'rgb(220, 38, 38)',
                        'rgb(124, 58, 237)',
                    ],
                ],
            ],
            'labels' => ['Users', 'Vendors', 'Budgets', 'Gardus', 'POPs'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
