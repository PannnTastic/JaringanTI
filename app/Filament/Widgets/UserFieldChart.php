<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Field;
use Filament\Widgets\ChartWidget;

class UserFieldChart extends ChartWidget
{
    protected static ?string $heading = 'Users by Field';

    protected static ?string $pollingInterval = '10s';

    public function getHeading(): string
    {
        return 'Pengguna per Bidang';
    }    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $fields = Field::all();
        
        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengguna',
                    'data' => $fields->map(fn ($field) => User::where('field_id', $field->field_id)->count())->toArray(),
                    'backgroundColor' => $fields->map(fn ($field) => match (strtolower($field->field_name)) {
                        'admin' => '#EF4444',  // danger
                        'manager' => '#F59E0B', // warning
                        'supervisor' => '#3B82F6', // info
                        default => '#6366F1', // primary
                    })->toArray(),
                    'borderColor' => '#9CA3AF',
                    'borderWidth' => 1,
                ]
            ],
            'labels' => $fields->pluck('field_name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}