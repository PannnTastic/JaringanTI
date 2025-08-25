<?php

namespace App\Filament\Widgets;

use App\Models\Knowledgebase;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopKnowledgebaseContributors extends BaseWidget
{
    protected static ?string $heading = 'Top Contributors Bulan Ini';
    protected static ?int $sort = 4;
    
    public function table(Table $table): Table
    {
        $currentMonth = Carbon::now()->startOfMonth();
        
        return $table
            ->query(
                User::select([
                    'users.user_id',
                    'users.name',
                    'users.email',
                    DB::raw('COUNT(knowledgebase.kb_id) as articles_count'),
                    DB::raw('MAX(knowledgebase.created_at) as last_article')
                ])
                ->leftJoin('knowledgebase', function ($join) use ($currentMonth) {
                    $join->on('users.user_id', '=', 'knowledgebase.user_id')
                         ->whereBetween('knowledgebase.created_at', [
                             $currentMonth,
                             $currentMonth->copy()->endOfMonth()
                         ])
                         ->whereNull('knowledgebase.deleted_at');
                })
                ->groupBy('users.user_id', 'users.name', 'users.email')
                ->havingRaw('COUNT(knowledgebase.kb_id) > 0')
                ->orderBy('articles_count', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->state(function ($record, $rowLoop) {
                        return $rowLoop->iteration;
                    })
                    ->badge()
                    ->color(function ($record, $rowLoop) {
                        return match ($rowLoop->iteration) {
                            1 => 'warning', // Gold
                            2 => 'gray',    // Silver  
                            3 => 'danger',  // Bronze
                            default => 'primary'
                        };
                    }),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama User')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('articles_count')
                    ->label('Artikel')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn ($state) => $state . ' artikel'),
                    
                Tables\Columns\TextColumn::make('last_article')
                    ->label('Artikel Terakhir')
                    ->dateTime('d/m/Y H:i')
                    ->color('info'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_latest_article')
                    ->label('Lihat Artikel Terbaru')
                    ->icon('heroicon-o-eye')
                    ->url(function ($record) {
                        // Cari artikel terbaru dari user ini
                        $latestKb = Knowledgebase::where('user_id', $record->user_id)
                            ->orderBy('created_at', 'desc')
                            ->first();
                        
                        return $latestKb ? '/kb/' . $latestKb->kb_id : '#';
                    })
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_all_articles')
                    ->label('Lihat Semua Artikel')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => '/admin/knowledgebases?tableSearch=' . urlencode($record->name))
                    ->openUrlInNewTab()
            ])
            ->paginated([5, 10]) // Show 5 or 10 records per page
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Belum Ada Kontributor')
            ->emptyStateDescription('Belum ada user yang membuat knowledgebase bulan ini.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
