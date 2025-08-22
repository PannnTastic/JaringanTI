<?php

namespace App\Filament\Widgets;

use App\Models\Knowledgebase;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KnowledgebaseUserStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        // Get the current month's start and end dates
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get users with their knowledgebase count for current month
        $userStats = User::select('users.name')
            ->selectRaw('COUNT(knowledgebases.id) as kb_count')
            ->leftJoin('knowledgebases', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('users.id', '=', 'knowledgebases.user_id')
                    ->whereBetween('knowledgebases.created_at', [$startOfMonth, $endOfMonth]);
            })
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('kb_count')
            ->get();

        // Create stats array
        $stats = [];

        foreach ($userStats as $userStat) {
            $stats[] = Stat::make($userStat->name, $userStat->kb_count)
                ->description('Knowledge Base ' . Carbon::now()->format('F Y'))
                ->descriptionIcon('heroicon-o-document-text')
                ->color($this->getColorBasedOnCount($userStat->kb_count))
                ->chart([
                    $this->getLastWeekCount($userStat->name),
                    $this->getLastWeekCount($userStat->name, -1),
                    $this->getLastWeekCount($userStat->name, -2),
                    $this->getLastWeekCount($userStat->name, -3),
                ])
                ->extraAttributes([
                    'class' => 'md:col-span-1',
                ]);
        }

        return $stats;
    }

    protected function getColorBasedOnCount(int $count): string
    {
        return match(true) {
            $count >= 5 => 'success',
            $count >= 3 => 'warning',
            $count > 0 => 'info',
            default => 'danger',
        };
    }

    protected function getLastWeekCount(string $userName, int $weeksAgo = 0): int
    {
        $startDate = Carbon::now()->subWeeks($weeksAgo)->startOfWeek();
        $endDate = Carbon::now()->subWeeks($weeksAgo)->endOfWeek();

        return Knowledgebase::whereHas('user', function ($query) use ($userName) {
            $query->where('name', $userName);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
