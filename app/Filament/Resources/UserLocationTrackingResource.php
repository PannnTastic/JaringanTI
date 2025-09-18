<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserLocationTrackingResource\Pages;
use App\Filament\Resources\UserLocationTrackingResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserLocationTrackingResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Location Management';

    protected static ?string $navigationLabel = 'User Tracking';
    protected static ?string $slug = 'tracking';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->disabled(),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->disabled(),
                        
                        Forms\Components\Select::make('role_id')
                            ->relationship('role', 'role_name')
                            ->disabled(),
                    ])->columns(3),
                
                Forms\Components\Section::make('Location Tracking Settings')
                    ->schema([
                        Forms\Components\Toggle::make('location_tracking_enabled')
                            ->label('Enable Location Tracking')
                            ->helperText('Allow this user to be tracked'),
                    ]),
                
                Forms\Components\Section::make('Current Location')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Current Latitude')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('longitude')
                            ->label('Current Longitude')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\Textarea::make('address')
                            ->label('Current Address')
                            ->disabled()
                            ->rows(2),
                        
                        Forms\Components\DateTimePicker::make('last_location_update')
                            ->label('Last Location Update')
                            ->disabled(),
                        
                        Forms\Components\DateTimePicker::make('last_seen')
                            ->label('Last Seen')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('user_photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => $record->avatar_url)
                    ->size(40),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('role.role_name')
                    ->label('Role')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('location_tracking_enabled')
                    ->label('Tracking')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Lat')
                    ->numeric(6)
                    ->placeholder('No location'),
                
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Lng')
                    ->numeric(6)
                    ->placeholder('No location'),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return $record->isOnline() ? 'online' : 'offline';
                    })
                    ->colors([
                        'success' => 'online',
                        'danger' => 'offline',
                    ]),
                
                Tables\Columns\TextColumn::make('last_seen')
                    ->label('Last Seen')
                    ->dateTime()
                    ->since()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('last_location_update')
                    ->label('Location Updated')
                    ->dateTime()
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('location_tracking_enabled')
                    ->label('Tracking Enabled')
                    ->trueLabel('Tracking Enabled')
                    ->falseLabel('Tracking Disabled')
                    ->native(false),
                
                Tables\Filters\Filter::make('online_users')
                    ->label('Online Users')
                    ->query(fn (Builder $query) => $query->online()),
                
                Tables\Filters\Filter::make('has_location')
                    ->label('Has Location Data')
                    ->query(fn (Builder $query) => $query->whereNotNull('current_latitude')->whereNotNull('current_longitude')),
                
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('role', 'role_name')
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_on_map')
                    ->label('View on Map')
                    ->icon('heroicon-o-map')
                    ->color('info')
                    ->url(fn ($record) => route('location.monitoring') . '?user=' . $record->user_id)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->current_latitude && $record->current_longitude),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('enable_tracking')
                        ->label('Enable Tracking')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['location_tracking_enabled' => true]);
                        }),
                    Tables\Actions\BulkAction::make('disable_tracking')
                        ->label('Disable Tracking')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['location_tracking_enabled' => false]);
                        }),
                ]),
            ])
            ->defaultSort('last_seen', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['role'])
            ->where('location_tracking_enabled', true)
            ->orWhereNotNull('latitude')
            ->orWhereNotNull('longitude');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserLocationTrackings::route('/'),
            'create' => Pages\CreateUserLocationTracking::route('/create'),
            'edit' => Pages\EditUserLocationTracking::route('/{record}/edit'),
        ];
    }
}
