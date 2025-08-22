<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Actions\Action as HeaderAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Notifications extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static string $view = 'filament.pages.notifications';
    
    protected static ?string $title = 'Notifications';
    
    protected static ?string $navigationLabel = 'Notifications';
    
    protected static ?int $navigationSort = 99;

    // Sembunyikan dari navigasi karena akan diakses via user menu
    protected static bool $shouldRegisterNavigation = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \Illuminate\Notifications\DatabaseNotification::query()
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', Auth::id())
                    ->latest()
            )
            ->columns([
                TextColumn::make('data.title')
                    ->label('Title')
                    ->weight('bold')
                    ->wrap(),
                TextColumn::make('data.body')
                    ->label('Message')
                    ->wrap()
                    ->limit(100),
                TextColumn::make('data.rejection_reason')
                    ->label('Rejection Reason')
                    ->wrap()
                    ->placeholder('-')
                    ->visible(fn ($record) => isset($record->data['rejection_reason']) && !empty($record->data['rejection_reason']))
                    ->color('danger'),
                TextColumn::make('created_at')
                    ->label('Received At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('read_at')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->read_at ? 'Read' : 'Unread')
                    ->badge()
                    ->color(fn ($record) => $record->read_at ? 'success' : 'warning'),
            ])
            ->actions([
                Action::make('view_detail')
                    ->label('View Details')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading(fn ($record) => $record->data['title'] ?? 'Notification Details')
                    ->modalContent(function ($record) {
                        $data = $record->data;
                        
                        return view('filament.pages.notification-detail', [
                            'notification' => $record,
                            'title' => $data['title'] ?? 'No title',
                            'body' => $data['body'] ?? 'No message',
                            'type' => $data['type'] ?? 'info',
                            'permitId' => $data['permit_id'] ?? null,
                            'rejectionReason' => $data['rejection_reason'] ?? null,
                            'createdAt' => $record->created_at,
                            'readAt' => $record->read_at,
                            'status' => $record->read_at ? 'Read' : 'Unread'
                        ]);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->action(function ($record) {
                        // Automatically mark as read when viewed
                        if (!$record->read_at) {
                            $record->markAsRead();
                        }
                    }),
                Action::make('mark_as_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function ($record) {
                        $record->markAsRead();
                    })
                    ->visible(fn ($record) => !$record->read_at),
                Action::make('go_to_permit')
                    ->label('View Permit')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => route('filament.admin.resources.permits.index') . '?tableSearch=' . ($record->data['permit_id'] ?? ''))
                    ->openUrlInNewTab(false)
                    ->visible(fn ($record) => isset($record->data['permit_id'])),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkAction::make('mark_all_read')
                    ->label('Mark Selected as Read')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->markAsRead();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Mark notifications as read')
                    ->modalDescription('Are you sure you want to mark the selected notifications as read?')
            ])
            ->emptyStateHeading('No notifications')
            ->emptyStateDescription('You have no notifications at the moment.')
            ->emptyStateIcon('heroicon-o-bell-slash');
    }

    protected function getHeaderActions(): array
    {
        return [
            HeaderAction::make('markAllAsRead')
                ->label('Mark All as Read')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function () {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    $user->unreadNotifications->markAsRead();
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Success')
                        ->body('All notifications marked as read.')
                        ->success()
                        ->send();
                })
                ->visible(function () {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    return $user->unreadNotifications()->count() > 0;
                })
                ->requiresConfirmation()
                ->modalHeading('Mark all notifications as read')
                ->modalDescription('Are you sure you want to mark all your notifications as read?')
                ->modalSubmitActionLabel('Yes, mark all as read'),
        ];
    }

    public function getTitle(): string 
    {
        $unreadCount = \Illuminate\Notifications\DatabaseNotification::query()
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->count();
        
        return $unreadCount > 0 
            ? "Notifications ({$unreadCount} unread)" 
            : 'Notifications';
    }
}
