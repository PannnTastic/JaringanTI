<?php

namespace App\Filament\Widgets;

use App\Models\Knowledgebase;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class KnowledgebaseMonthlyChart extends ChartWidget
{
    protected static ?string $heading = 'Knowledgebase Bulanan';
    protected static ?int $sort = 3;
    
    protected function getData(): array
    {
        $months = [];
        $data = [];
        
        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $count = Knowledgebase::whereBetween('created_at', [
                $month->startOfMonth()->copy(),
                $month->endOfMonth()->copy()
            ])->count();
            
            $data[] = $count;
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Knowledgebase Dibuat',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)', 
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(236, 72, 153, 1)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Artikel'
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan'
                    ]
                ]
            ],
        ];
    }
}
