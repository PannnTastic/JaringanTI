<?php

namespace App\Filament\Widgets;

use App\Models\Knowledgebase;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KnowledgebaseMonthlyStats extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Total KB bulan ini
        $currentMonthTotal = Knowledgebase::whereBetween('created_at', [
            $currentMonth,
            $currentMonth->copy()->endOfMonth()
        ])->count();
        
        // Total KB bulan lalu
        $previousMonthTotal = Knowledgebase::whereBetween('created_at', [
            $previousMonth,
            $previousMonth->copy()->endOfMonth()
        ])->count();
        
        // Hitung persentase perubahan
        $changePercentage = $previousMonthTotal > 0 
            ? (($currentMonthTotal - $previousMonthTotal) / $previousMonthTotal) * 100 
            : ($currentMonthTotal > 0 ? 100 : 0);
        
        // Top contributor bulan ini
        $topContributor = Knowledgebase::select('users.name', DB::raw('COUNT(*) as kb_count'))
            ->join('users', 'knowledgebase.user_id', '=', 'users.user_id')
            ->whereBetween('knowledgebase.created_at', [
                $currentMonth,
                $currentMonth->copy()->endOfMonth()
            ])
            ->groupBy('knowledgebase.user_id', 'users.name')
            ->orderBy('kb_count', 'desc')
            ->first();
            
        // Total user yang berkontribusi bulan ini
        $activeContributors = Knowledgebase::whereBetween('created_at', [
            $currentMonth,
            $currentMonth->copy()->endOfMonth()
        ])->distinct('user_id')->count('user_id');
        
        // Total KB keseluruhan
        $totalKnowledgebase = Knowledgebase::count();
        
        return [
            Stat::make('Artikel Dibuat Bulan Ini', $currentMonthTotal)
                ->description($changePercentage >= 0 
                    ? '+' . number_format($changePercentage, 1) . '% dari bulan lalu'
                    : number_format($changePercentage, 1) . '% dari bulan lalu'
                )
                ->descriptionIcon($changePercentage >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($changePercentage >= 0 ? 'success' : 'danger')
                ->chart($this->getMonthlyChart()),
                
            Stat::make('Top Contributor', $topContributor ? $topContributor->name : 'Tidak ada')
                ->description($topContributor 
                    ? $topContributor->kb_count . ' artikel bulan ini'
                    : 'Belum ada kontribusi bulan ini'
                )
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
                
            Stat::make('Kontributor Aktif', $activeContributors)
                ->description('User yang berkontribusi bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
                
            Stat::make('Total Knowledgebase', $totalKnowledgebase)
                ->description('Total keseluruhan artikel')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),
        ];
    }
    
    private function getMonthlyChart(): array
    {
        // Chart untuk 7 hari terakhir
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Knowledgebase::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }
        
        return $data;
    }
    
    protected function getColumns(): int
    {
        return 2; // 2 kolom untuk tampilan yang lebih rapi
    }
}
