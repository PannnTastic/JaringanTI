<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Permit;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\PermitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermitResource\RelationManagers;

class PermitResource extends Resource
{
    protected static ?string $model = Permit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Urutan approval yang harus diikuti
    protected static array $approvalSequence = [
        'staff it network',
        'asisten manajer network', 
        'infra manager'
    ];

    // Method untuk mendapatkan role approval berikutnya - HARDCODE
    public static function getNextApprovalRole($record): ?string
    {
        // Cek staff it network dulu (case insensitive)
        $staffRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
        if ($staffRole) {
            $staffApproval = $record->approvers()
                ->where('approvers.role_id', $staffRole->role_id)
                ->withPivot(['approver_status'])
                ->first();
            
            if ($staffApproval && $staffApproval->pivot->approver_status == 0) {
                return $staffRole->role_name; // Return nama role yang sebenarnya dari database
            }
        }
        
        // Kalau staff sudah approve, cek asisten manajer (case insensitive)
        $asistenRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['asisten manajer network'])->first();
        if ($asistenRole) {
            $asistenApproval = $record->approvers()
                ->where('approvers.role_id', $asistenRole->role_id)
                ->withPivot(['approver_status'])
                ->first();
            
            if ($asistenApproval && $asistenApproval->pivot->approver_status == 0) {
                return $asistenRole->role_name; // Return nama role yang sebenarnya dari database
            }
        }
        
        // Kalau asisten sudah approve, cek infra manager (case insensitive)
        $infraRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['infra manager'])->first();
        if ($infraRole) {
            $infraApproval = $record->approvers()
                ->where('approvers.role_id', $infraRole->role_id)
                ->withPivot(['approver_status'])
                ->first();
            
            if ($infraApproval && $infraApproval->pivot->approver_status == 0) {
                return $infraRole->role_name; // Return nama role yang sebenarnya dari database
            }
        }
        
        return null; // Semua sudah approve
    }

    // Method untuk cek apakah user bisa approve berdasarkan urutan - HARDCODE
    public static function canUserApprove($record, $userRole): bool
    {
        if (!$userRole) return false;
        
        $userRoleName = strtolower($userRole->role_name); // Convert ke lowercase untuk comparison
        
        // Jika user adalah staff it network
        if ($userRoleName === 'staff it network') {
            $staffRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
            if ($staffRole) {
                $staffApproval = $record->approvers()
                    ->where('approvers.role_id', $staffRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();
                
                return $staffApproval && $staffApproval->pivot->approver_status == 0;
            }
        }
        
        // Jika user adalah asisten manajer network
        if ($userRoleName === 'asisten manajer network') {
            // Cek dulu apakah asisten ada di approver list
            $asistenRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['asisten manajer network'])->first();
            if (!$asistenRole) {
                return false;
            }
            
            $asistenApproval = $record->approvers()
                ->where('approvers.role_id', $asistenRole->role_id)
                ->withPivot(['approver_status'])
                ->first();
            
            // Jika asisten tidak ada di approver list, tidak bisa approve
            if (!$asistenApproval) {
                return false;
            }
            
            // Jika asisten sudah approve, tidak bisa approve lagi
            if ($asistenApproval->pivot->approver_status == 1) {
                return false;
            }
            
            // Cek apakah staff sudah approve (jika ada staff di approver list)
            $staffRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
            if ($staffRole) {
                $staffApproval = $record->approvers()
                    ->where('approvers.role_id', $staffRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();
                
                // Jika ada staff di approver list dan belum approve, asisten tidak bisa approve
                if ($staffApproval && $staffApproval->pivot->approver_status == 0) {
                    return false;
                }
            }
            
            // Jika sampai sini, asisten bisa approve
            return true;
        }
        
        // Jika user adalah infra manager
        if ($userRoleName === 'infra manager') {
            // Cek dulu apakah infra ada di approver list
            $infraRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['infra manager'])->first();
            if (!$infraRole) {
                return false;
            }
            
            $infraApproval = $record->approvers()
                ->where('approvers.role_id', $infraRole->role_id)
                ->withPivot(['approver_status'])
                ->first();
            
            // Jika infra tidak ada di approver list, tidak bisa approve
            if (!$infraApproval) {
                return false;
            }
            
            // Jika infra sudah approve, tidak bisa approve lagi
            if ($infraApproval->pivot->approver_status == 1) {
                return false;
            }
            
            // Cek apakah staff dan asisten sudah approve (jika ada di approver list)
            $staffRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
            $asistenRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['asisten manajer network'])->first();
            
            if ($staffRole) {
                $staffApproval = $record->approvers()
                    ->where('approvers.role_id', $staffRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();
                
                // Jika ada staff di approver list dan belum approve, infra tidak bisa approve
                if ($staffApproval && $staffApproval->pivot->approver_status == 0) {
                    return false;
                }
            }
            
            if ($asistenRole) {
                $asistenApproval = $record->approvers()
                    ->where('approvers.role_id', $asistenRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();
                
                // Jika ada asisten di approver list dan belum approve, infra tidak bisa approve
                if ($asistenApproval && $asistenApproval->pivot->approver_status == 0) {
                    return false;
                }
            }
            
            // Jika sampai sini, infra bisa approve
            return true;
        }
        
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(function() {
                        return Auth::user()?->user_id;
                    }),
                    
                Forms\Components\Select::make('substation_id')
                    ->label('Substation')
                    ->required()
                    ->relationship('substations', 'substation_name')
                    ->searchable(),
                    
                Forms\Components\Toggle::make('permit_status')
                    ->label('Status Permit')
                    ->default(false)
                    ->disabled()
                    ->helperText('Status akan diubah setelah semua role menyetujui'),
                    
                Forms\Components\Select::make('roles')
                    ->label('Role Approver')
                    ->multiple()
                    ->options(Role::whereIn('role_name', ['Admin','Staff IT Network', 'Asisten Manajer Network','Infra Manager'])
                        ->pluck('role_name', 'role_id'))
                    ->required()
                    ->dehydrated(false) // Tidak disimpan ke tabel permits
                    ->helperText('Pilih role yang harus menyetujui permit ini')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Nama Pemohon'),
                Tables\Columns\TextColumn::make('substations.substation_name')
                    ->label('Nama Substation'),
                Tables\Columns\IconColumn::make('permit_status')
                    ->label('Status Permit')
                    ->boolean(),
                Tables\Columns\TextColumn::make('approvers_status')
                    ->label('Status Approval')
                    ->getStateUsing(function (Permit $record) {
                        $approvers = $record->approvers()
                            ->withPivot(['approver_status', 'approved_at'])
                            ->get();
                        
                        $approved = $approvers->where('pivot.approver_status', 1)->count();
                        $total = $approvers->count();
                        
                        return "{$approved}/{$total} Approved";
                    })
                    ->badge()
                    ->color(function (Permit $record) {
                        $approvers = $record->approvers()
                            ->withPivot(['approver_status'])
                            ->get();
                        
                        $approved = $approvers->where('pivot.approver_status', 1)->count();
                        $total = $approvers->count();
                        
                        if ($approved == $total) {
                            return 'success';
                        } elseif ($approved > 0) {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    }),
                Tables\Columns\TextColumn::make('next_approver')
                    ->label('Menunggu Approval')
                    ->getStateUsing(function (Permit $record) {
                        if ($record->permit_status) {
                            return 'Selesai';
                        }
                        
                        $nextRole = self::getNextApprovalRole($record);
                        return $nextRole ? ucwords(str_replace('_', ' ', $nextRole)) : 'Semua Selesai';
                    })
                    ->badge()
                    ->color(function (Permit $record) {
                        if ($record->permit_status) {
                            return 'success';
                        }
                        
                        $nextRole = self::getNextApprovalRole($record);
                        return $nextRole ? 'warning' : 'success';
                    }),
                Tables\Columns\TextColumn::make('my_approval_status')
                    ->label('Status Approval Saya')
                    ->getStateUsing(function (Permit $record) {
                        $user = Auth::user();
                        $userRole = $user->role;
                        
                        // Jika user adalah admin
                        if ($userRole && strtolower($userRole->role_name) === 'admin') {
                            return 'Admin (View Only)';
                        }
                        
                        // Jika user adalah pembuat permit
                        if ($record->user_id == $user->user_id) {
                            return 'Pembuat Permit';
                        }
                        
                        if (!$userRole) {
                            return 'N/A';
                        }
                        
                        $approval = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->withPivot(['approver_status', 'approved_at'])
                            ->first();
                        
                        if (!$approval) {
                            return 'Tidak Relevan';
                        }
                        
                        if ($approval->pivot->approver_status == 1) {
                            $approvedAt = $approval->pivot->approved_at;
                            $formattedDate = $approvedAt ? \Carbon\Carbon::parse($approvedAt)->format('d/m/Y H:i') : '';
                            return 'Disetujui' . ($formattedDate ? ' (' . $formattedDate . ')' : '');
                        } else {
                            // Cek apakah giliran user untuk approve
                            $canApprove = self::canUserApprove($record, $userRole);
                            return $canApprove ? 'Giliran Saya' : 'Menunggu Urutan';
                        }
                    })
                    ->badge()
                    ->color(function (Permit $record) {
                        $user = Auth::user();
                        $userRole = $user->role;
                        
                        // Jika user adalah admin
                        if ($userRole && strtolower($userRole->role_name) === 'admin') {
                            return 'primary';
                        }
                        
                        // Jika user adalah pembuat permit
                        if ($record->user_id == $user->user_id) {
                            return 'info';
                        }
                        
                        if (!$userRole) {
                            return 'gray';
                        }
                        
                        $approval = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->withPivot(['approver_status'])
                            ->first();
                        
                        if (!$approval) {
                            return 'gray';
                        }
                        
                        if ($approval->pivot->approver_status == 1) {
                            return 'success';
                        } else {
                            // Cek apakah giliran user untuk approve
                            $canApprove = self::canUserApprove($record, $userRole);
                            return $canApprove ? 'warning' : 'gray';
                        }
                    }),
                Tables\Columns\TextColumn::make('approvers')
                    ->label('Required Approvers')
                    ->getStateUsing(fn (Permit $record) => $record->approvers->pluck('role_name')->implode(', ')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Status Approval Saya')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Sudah Disetujui',
                        'all' => 'Semua'
                    ])
                    ->default('all')
                    ->query(function (Builder $query, array $data): Builder {
                        $userRole = Auth::user()->role;
                        
                        if (!$userRole || !isset($data['value'])) {
                            return $query;
                        }
                        
                        return match($data['value']) {
                            'pending' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                  ->where('approvers.approver_status', 0);
                            }),
                            'approved' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                  ->where('approvers.approver_status', 1);
                            }),
                            default => $query
                        };
                    }),
                Tables\Filters\SelectFilter::make('approval_queue')
                    ->label('Urutan Approval')
                    ->options([
                        'my_turn' => 'Giliran Saya',
                        'waiting' => 'Menunggu Urutan',
                        'completed' => 'Selesai Approval'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $user = Auth::user();
                        $userRole = $user->role;
                        
                        if (!$userRole || !isset($data['value']) || strtolower($userRole->role_name) === 'admin') {
                            return $query;
                        }
                        
                        return match($data['value']) {
                            'my_turn' => $query->where(function ($mainQuery) use ($userRole) {
                                $mainQuery->whereHas('approvers', function ($q) use ($userRole) {
                                    $q->where('approvers.role_id', $userRole->role_id)
                                      ->where('approvers.approver_status', 0);
                                });
                                
                                // HARDCODE: Cek apakah memang giliran user
                                $userRoleName = $userRole->role_name;
                                if ($userRoleName === 'Staff IT Network') {
                                    // Staff selalu bisa approve jika belum approve
                                    return;
                                } elseif ($userRoleName === 'Asisten Manajer Network') {
                                    // Asisten hanya bisa jika staff sudah approve atau tidak ada staff
                                    $mainQuery->whereNotExists(function ($staffQuery) {
                                        $staffQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->where('roles.role_name', 'Staff IT Network')
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'Infra Manager') {
                                    // Infra manager hanya bisa jika staff dan asisten sudah approve
                                    $mainQuery->whereNotExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereIn('roles.role_name', ['Staff IT Network', 'Asisten Manajer Network'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                }
                            }),
                            'waiting' => $query->where(function ($mainQuery) use ($userRole) {
                                $mainQuery->whereHas('approvers', function ($q) use ($userRole) {
                                    $q->where('approvers.role_id', $userRole->role_id)
                                      ->where('approvers.approver_status', 0);
                                });
                                
                                // HARDCODE: Ada role sebelumnya yang belum approve
                                $userRoleName = $userRole->role_name;
                                if ($userRoleName === 'Asisten Manajer Network') {
                                    $mainQuery->whereExists(function ($staffQuery) {
                                        $staffQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->where('roles.role_name', 'Staff IT Network')
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'Infra Manager') {
                                    $mainQuery->whereExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereIn('roles.role_name', ['Staff IT Network', 'Asisten Manajer Network'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } else {
                                    // Staff tidak pernah menunggu urutan
                                    $mainQuery->whereRaw('1 = 0'); // False condition
                                }
                            }),
                            'completed' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                  ->where('approvers.approver_status', 1);
                            }),
                            default => $query
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approver_status')
                    ->label('Approve')
                    ->action(function (Permit $record) {
                        $userRole = Auth::user()->role;
                        
                        if ($userRole && self::canUserApprove($record, $userRole)) {
                            // Update pivot record untuk role yang user miliki
                            $record->approvers()
                                ->updateExistingPivot($userRole->role_id, [
                                    'approver_status' => 1,
                                    'approved_at' => now()
                                ]);
                            
                            // Cek apakah semua approver sudah menyetujui
                            $totalApprovers = $record->approvers()->count();
                            $approvedCount = $record->approvers()
                                ->where('approvers.approver_status', 1)
                                ->count();
                            
                            // Jika semua sudah approve, update permit status
                            if ($approvedCount == $totalApprovers) {
                                $record->update(['permit_status' => true]);
                            }
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Apakah Anda yakin ingin menyetujui permit ini? Approval akan dilakukan sesuai urutan yang telah ditentukan.')
                    ->color('success')
                    ->visible(function (Permit $record) {
                        $userRole = Auth::user()->role;
                        
                        if (!$userRole) {
                            return false;
                        }
                        
                        // Cek apakah user memiliki role yang ada di approvers list
                        $hasApprovalRole = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->where('approvers.approver_status', 0)
                            ->exists();
                        
                        // Dan cek apakah sesuai urutan approval
                        return $hasApprovalRole && self::canUserApprove($record, $userRole);
                    })

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPermits::route('/'),
            'create' => Pages\CreatePermit::route('/create'),
            'edit' => Pages\EditPermit::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('permits');
    }

    public static function canViewAny(): bool
    {
        return \App\Helpers\PermissionHelper::canAccessResource('permits');
    }
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        return parent::getEloquentQuery()
            ->where(function ($query) use ($user) {
                // Admin bisa melihat semua permit
                if ($user->role && strtolower($user->role->role_name) === 'admin') {
                    // Admin melihat semua permit, tidak perlu filter
                    return;
                }
                
                // Tampilkan permit yang dibuat oleh user sendiri
                $query->where('user_id', $user->user_id);
                
                // ATAU permit dimana user adalah approver (berdasarkan role)
                if ($user->role) {
                    $query->orWhereHas('approvers', function ($subQuery) use ($user) {
                        $subQuery->where('approvers.role_id', $user->role->role_id);
                    });
                }
            });
    }

}
