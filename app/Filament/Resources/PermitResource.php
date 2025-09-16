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
use Illuminate\Support\Facades\DB;
use App\Services\PermitNotificationService;
use App\Filament\Resources\PermitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermitResource\RelationManagers;

class PermitResource extends Resource
{
    protected static ?string $model = Permit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Permohonan';

    protected static ?string $modelLabel = 'Permohonan';

    // Urutan approval yang harus diikuti
    protected static array $approvalSequence = [
        'staff perencanaan pengadaan',
        'staff it network',
        'asisten manajer network',
        'infra manager'
    ];

    // Method untuk mendapatkan role approval berikutnya - HARDCODE
    public static function getNextApprovalRole($record): ?string
    {
        // Cek staff perencanaan pengadaan dulu (case insensitive)
        $staffPerencanaanRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff perencanaan pengadaan'])->first();
        if ($staffPerencanaanRole) {
            $staffApproval = $record->approvers()
                ->where('approvers.role_id', $staffPerencanaanRole->role_id)
                ->withPivot(['approver_status'])
                ->first();

            if ($staffApproval && $staffApproval->pivot->approver_status == 0) {
                return $staffPerencanaanRole->role_name;
            }
        }

        // Cek staff it network
        $staffNetworkRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
        if ($staffNetworkRole) {
            $staffApproval = $record->approvers()
                ->where('approvers.role_id', $staffNetworkRole->role_id)
                ->withPivot(['approver_status'])
                ->first();

            if ($staffApproval && $staffApproval->pivot->approver_status == 0) {
                return $staffNetworkRole->role_name;
            }
        }

        // Cek asisten manajer network
        $asistenRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['asisten manajer network'])->first();
        if ($asistenRole) {
            $asistenApproval = $record->approvers()
                ->where('approvers.role_id', $asistenRole->role_id)
                ->withPivot(['approver_status'])
                ->first();

            if ($asistenApproval && $asistenApproval->pivot->approver_status == 0) {
                return $asistenRole->role_name;
            }
        }

        // Cek infra manager
        $infraRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['infra manager'])->first();
        if ($infraRole) {
            $infraApproval = $record->approvers()
                ->where('approvers.role_id', $infraRole->role_id)
                ->withPivot(['approver_status'])
                ->first();

            if ($infraApproval && $infraApproval->pivot->approver_status == 0) {
                return $infraRole->role_name;
            }
        }

        return null; // Semua sudah approve
    }

    // Method untuk cek apakah user bisa approve berdasarkan urutan - HARDCODE
    public static function canUserApprove($record, $userRole): bool
    {
        if (!$userRole) return false;

        $userRoleName = strtolower($userRole->role_name); // Convert ke lowercase untuk comparison

        // Jika user adalah staff perencanaan pengadaan
        if ($userRoleName === 'staff perencanaan pengadaan') {
            $staffPerencanaanRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff perencanaan pengadaan'])->first();
            if ($staffPerencanaanRole) {
                $staffApproval = $record->approvers()
                    ->where('approvers.role_id', $staffPerencanaanRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();

                return $staffApproval && $staffApproval->pivot->approver_status == 0;
            }
        }

        // Jika user adalah staff it network
        if ($userRoleName === 'staff it network') {
            // Cek dulu apakah staff perencanaan pengadaan sudah approve
            $staffPerencanaanRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff perencanaan pengadaan'])->first();
            if ($staffPerencanaanRole) {
                $staffPerencanaanApproval = $record->approvers()
                    ->where('approvers.role_id', $staffPerencanaanRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();

                // Jika staff perencanaan belum approve, staff it tidak bisa approve
                if ($staffPerencanaanApproval && $staffPerencanaanApproval->pivot->approver_status == 0) {
                    return false;
                }
            }
            $staffNetworkRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['staff it network'])->first();
            if ($staffNetworkRole) {
                $staffNetworkApproval = $record->approvers()
                    ->where('approvers.role_id', $staffNetworkRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();

                return $staffNetworkApproval && $staffNetworkApproval->pivot->approver_status == 0;
            }
        }

        // Jika user adalah asisten manajer network
        if ($userRoleName === 'asisten manajer network') {
            // Cek dulu apakah staff perencanaan dan staff it sudah approve
            $prevRoles = ['staff perencanaan pengadaan', 'staff it network'];
            foreach ($prevRoles as $roleName) {
                $prevRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', [$roleName])->first();
                if ($prevRole) {
                    $prevApproval = $record->approvers()
                        ->where('approvers.role_id', $prevRole->role_id)
                        ->withPivot(['approver_status'])
                        ->first();

                    if ($prevApproval && $prevApproval->pivot->approver_status == 0) {
                        return false;
                    }
                }
            }

            $asistenRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['asisten manajer network'])->first();
            if ($asistenRole) {
                $asistenApproval = $record->approvers()
                    ->where('approvers.role_id', $asistenRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();

                return $asistenApproval && $asistenApproval->pivot->approver_status == 0;
            }
        }

        // Jika user adalah infra manager
        if ($userRoleName === 'infra manager') {
            // Cek dulu apakah semua role sebelumnya sudah approve
            $prevRoles = ['staff perencanaan pengadaan', 'staff it network', 'asisten manajer network'];
            foreach ($prevRoles as $roleName) {
                $prevRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', [$roleName])->first();
                if ($prevRole) {
                    $prevApproval = $record->approvers()
                        ->where('approvers.role_id', $prevRole->role_id)
                        ->withPivot(['approver_status'])
                        ->first();

                    if ($prevApproval && $prevApproval->pivot->approver_status == 0) {
                        return false;
                    }
                }
            }

            $infraRole = \App\Models\Role::whereRaw('LOWER(role_name) = ?', ['infra manager'])->first();
            if ($infraRole) {
                $infraApproval = $record->approvers()
                    ->where('approvers.role_id', $infraRole->role_id)
                    ->withPivot(['approver_status'])
                    ->first();

                return $infraApproval && $infraApproval->pivot->approver_status == 0;
            }
        }

        return false;
    }

    // Method untuk mendapatkan informasi rejection detail
    public static function getRejectionDetails($record): ?array
    {
        if ($record->getAttribute('permit_status') != -1) {
            return null;
        }

        $rejectedApprover = $record->approvers()
            ->wherePivot('approver_status', -1)
            ->withPivot(['rejection_reason', 'approved_at'])
            ->first();

        return [
            'rejected_by' => $record->getAttribute('rejected_by'),
            'rejected_at' => $record->getAttribute('rejected_at'),
            'rejection_reason' => $rejectedApprover?->pivot?->rejection_reason,
        ];
    }

    // Method untuk reset approval status (jika diperlukan untuk re-approval)
    public static function resetApprovals($record): bool
    {
        try {
            DB::beginTransaction();

            // Reset semua approval status kembali ke 0
            $record->approvers()->updateExistingPivot(
                $record->approvers->pluck('role_id')->toArray(),
                [
                    'approver_status' => 0,
                    'approved_at' => null,
                    'rejection_reason' => null
                ]
            );

            // Reset permit status
            $record->update([
                'permit_status' => 0,
                'rejected_by' => null,
                'rejected_at' => null
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to reset approvals', [
                'permit_id' => $record->permit_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(function () {
                        return Auth::id();
                    }),

                Forms\Components\Select::make('substation_id')
                    ->label('Substation')
                    ->required()
                    ->relationship('substations', 'substation_name'),

                Forms\Components\Toggle::make('permit_status')
                    ->label('Status Permit')
                    ->default(false)
                    ->disabled()
                    ->helperText('Status akan diubah setelah semua role menyetujui'),

                Forms\Components\Select::make('approver_roles')
                    ->label('Role Approver')
                    ->multiple()
                    ->options(Role::whereIn('role_name', ['Staff Perencanaan Pengadaan', 'Staff IT Network', 'Asisten Manajer Network', 'Infra Manager'])
                        ->pluck('role_name', 'role_id'))
                    ->required()
                    ->helperText('Pilih role yang harus menyetujui permit ini')
                    ->dehydrated(false) // Mencegah field ini disimpan langsung ke database
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('permit_id')
                    ->label('ID Permit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Nama Pemohon')
                    ->getStateUsing(fn(?Permit $record) => $record?->users?->name ?? ''),
                Tables\Columns\TextColumn::make('substations.substation_name')
                    ->label('Nama Substation')
                    ->getStateUsing(fn(?Permit $record) => $record?->substations?->substation_name ?? ''),
                Tables\Columns\IconColumn::make('permit_status')
                    ->label('Status Permit')
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record) return 'pending';

                        $status = $record->getAttribute('permit_status');
                        return match ($status) {
                            1 => 'approved',
                            -1 => 'rejected',
                            default => 'pending'
                        };
                    })
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-x-circle' => 'rejected',
                    ])
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->tooltip(function (?Permit $record) {
                        if (!$record) return null;

                        $status = $record->getAttribute('permit_status');
                        return match ($status) {
                            1 => 'Permit Disetujui',
                            -1 => 'Permit Ditolak' . ($record->getAttribute('rejected_by') ? ' oleh ' . $record->getAttribute('rejected_by') : ''),
                            default => 'Menunggu Persetujuan'
                        };
                    }),
                Tables\Columns\TextColumn::make('approvers_status')
                    ->label('Status Approval')
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record) return 'N/A';

                        $approvers = $record->approvers()
                            ->withPivot(['approver_status', 'approved_at'])
                            ->get();

                        $approved = $approvers->where('pivot.approver_status', 1)->count();
                        $total = $approvers->count();

                        return "{$approved}/{$total} Approved";
                    })
                    ->badge()
                    ->color(function (?Permit $record) {
                        if (!$record) return 'gray';

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
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record) return 'N/A';

                        $status = $record->getAttribute('permit_status');

                        if ($status == 1) {
                            return 'Disetujui';
                        } elseif ($status == -1) {
                            $rejectedBy = $record->getAttribute('rejected_by');
                            return 'Ditolak' . ($rejectedBy ? ' oleh ' . $rejectedBy : '');
                        }

                        $nextRole = self::getNextApprovalRole($record);
                        return $nextRole ? ucwords(str_replace('_', ' ', $nextRole)) : 'Semua Selesai';
                    })
                    ->badge()
                    ->color(function (?Permit $record) {
                        if (!$record) return 'gray';

                        $status = $record->getAttribute('permit_status');

                        if ($status == 1) {
                            return 'success';
                        } elseif ($status == -1) {
                            return 'danger';
                        }

                        $nextRole = self::getNextApprovalRole($record);
                        return $nextRole ? 'warning' : 'success';
                    }),
                Tables\Columns\TextColumn::make('my_approval_status')
                    ->label('Status Approval Saya')
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record) return 'N/A';

                        $user = Auth::user();
                        $userRole = $user->role ?? null;

                        // Jika user adalah admin
                        if ($userRole && strtolower($userRole->role_name) === 'administrator') {
                            return 'Admin (View Only)';
                        }

                        // Jika user adalah pembuat permit
                        if ($record->getAttribute('user_id') == Auth::id()) {
                            $status = $record->getAttribute('permit_status');
                            return match ($status) {
                                1 => 'Permit Disetujui',
                                -1 => 'Permit Ditolak',
                                default => 'Menunggu Approval'
                            };
                        }

                        if (!$userRole) {
                            return 'N/A';
                        }

                        $approval = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->withPivot(['approver_status', 'approved_at', 'rejection_reason'])
                            ->first();

                        if (!$approval) {
                            return 'Tidak Relevan';
                        }

                        if ($approval->pivot->approver_status == 1) {
                            $approvedAt = $approval->pivot->approved_at;
                            $formattedDate = $approvedAt ? \Carbon\Carbon::parse($approvedAt)->format('d/m/Y H:i') : '';
                            return 'Disetujui' . ($formattedDate ? ' (' . $formattedDate . ')' : '');
                        } elseif ($approval->pivot->approver_status == -1) {
                            $rejectedAt = $approval->pivot->approved_at;
                            $formattedDate = $rejectedAt ? \Carbon\Carbon::parse($rejectedAt)->format('d/m/Y H:i') : '';
                            return 'Ditolak' . ($formattedDate ? ' (' . $formattedDate . ')' : '');
                        } else {
                            // Status 0 - belum diproses
                            $permitStatus = $record->getAttribute('permit_status');
                            if ($permitStatus == -1) {
                                return 'Permit Ditolak';
                            }

                            // Cek apakah giliran user untuk approve
                            $canApprove = self::canUserApprove($record, $userRole);
                            return $canApprove ? 'Giliran Saya' : 'Menunggu Urutan';
                        }
                    })
                    ->badge()
                    ->color(function (?Permit $record) {
                        if (!$record) return 'gray';

                        $user = Auth::user();
                        $roleId = $user->role_id ?? null;
                        $userRole = $roleId ? DB::table('roles')->where('role_id', $roleId)->first() : null;

                        // Jika user adalah admin
                        if ($userRole && strtolower($userRole->role_name) === 'administrator') {
                            return 'primary';
                        }

                        // Jika user adalah pembuat permit
                        if ($record->getAttribute('user_id') == Auth::id()) {
                            $status = $record->getAttribute('permit_status');
                            return match ($status) {
                                1 => 'success',
                                -1 => 'danger',
                                default => 'warning'
                            };
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
                        } elseif ($approval->pivot->approver_status == -1) {
                            return 'danger';
                        } else {
                            // Status 0 - belum diproses
                            $permitStatus = $record->getAttribute('permit_status');
                            if ($permitStatus == -1) {
                                return 'gray';
                            }

                            // Cek apakah giliran user untuk approve
                            $canApprove = self::canUserApprove($record, $userRole);
                            return $canApprove ? 'warning' : 'gray';
                        }
                    })
                    ->tooltip(function (?Permit $record) {
                        if (!$record) return null;

                        $user = Auth::user();
                        $userRole = $user->role ?? null;

                        if (!$userRole || $record->getAttribute('user_id') == Auth::id()) {
                            return null;
                        }

                        $approval = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->withPivot(['rejection_reason'])
                            ->first();

                        if ($approval && $approval->pivot->approver_status == -1 && $approval->pivot->rejection_reason) {
                            return 'Alasan: ' . $approval->pivot->rejection_reason;
                        }

                        return null;
                    }),
                Tables\Columns\TextColumn::make('approvers')
                    ->label('Required Approvers')
                    ->getStateUsing(fn(?Permit $record) => $record?->approvers?->pluck('role_name')->implode(', ') ?? ''),
                Tables\Columns\TextColumn::make('rejection_details')
                    ->label('Detail Penolakan')
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record || $record->getAttribute('permit_status') != -1) {
                            return null;
                        }

                        $rejectedBy = $record->getAttribute('rejected_by');
                        $rejectedAt = $record->getAttribute('rejected_at');

                        $details = '';
                        if ($rejectedBy) {
                            $details .= 'Ditolak oleh: ' . $rejectedBy;
                        }
                        if ($rejectedAt) {
                            $formattedDate = \Carbon\Carbon::parse($rejectedAt)->format('d/m/Y H:i');
                            $details .= ($details ? ' (' . $formattedDate . ')' : $formattedDate);
                        }

                        // Cari alasan penolakan dari approvers pivot
                        $rejectionReason = $record->approvers()
                            ->wherePivot('approver_status', -1)
                            ->withPivot(['rejection_reason'])
                            ->first()?->pivot?->rejection_reason;

                        if ($rejectionReason) {
                            $details .= "\nAlasan: " . $rejectionReason;
                        }

                        return $details ?: null;
                    })
                    ->wrap()
                    ->visible(fn(?Permit $record) => $record?->getAttribute('permit_status') == -1)
                    ->color('danger'),
                Tables\Columns\TextColumn::make('substation_documents')
                    ->label('Dokumen Substation')
                    ->getStateUsing(function (?Permit $record) {
                        if (!$record || !$record->substations) {
                            return 'Tidak ada substation';
                        }

                        $documents = $record->substations->documents;

                        if ($documents->isEmpty()) {
                            return 'Belum ada dokumen';
                        }

                        return $documents->count() . ' dokumen tersedia';
                    })
                    ->badge()
                    ->color(function (?Permit $record) {
                        if (!$record || !$record->substations) {
                            return 'gray';
                        }

                        $documentCount = $record->substations->documents->count();

                        if ($documentCount == 0) {
                            return 'danger';
                        } elseif ($documentCount < 3) {
                            return 'warning';
                        } else {
                            return 'success';
                        }
                    })
                    ->tooltip(function (?Permit $record) {
                        if (!$record || !$record->substations || $record->substations->documents->isEmpty()) {
                            return 'Klik action "Lihat Dokumen" untuk melihat detail dokumen';
                        }

                        $documents = $record->substations->documents->take(5); // Show max 5 documents in tooltip
                        $docNames = $documents->pluck('doc_name')->implode("\n• ");
                        $moreCount = $record->substations->documents->count() - 5;

                        $tooltip = "Dokumen tersedia:\n• " . $docNames;

                        if ($moreCount > 0) {
                            $tooltip .= "\n... dan " . $moreCount . " dokumen lainnya";
                        }

                        $tooltip .= "\n\nKlik action 'Lihat Dokumen' untuk detail lengkap";

                        return $tooltip;
                    })
                    ->searchable(false)
                    ->sortable(false),
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
                Tables\Filters\SelectFilter::make('permit_status')
                    ->label('Status Permit')
                    ->options([
                        '0' => 'Menunggu Approval',
                        '1' => 'Disetujui',
                        '-1' => 'Ditolak'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value']) && $data['value'] !== '') {
                            return $query->where('permit_status', $data['value']);
                        }
                        return $query;
                    }),
                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Status Approval Saya')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Sudah Disetujui',
                        'rejected' => 'Sudah Ditolak',
                        'all' => 'Semua'
                    ])
                    ->default('all')
                    ->query(function (Builder $query, array $data): Builder {
                        $userRole = Auth::user()->role;

                        if (!$userRole || !isset($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'pending' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                    ->where('approvers.approver_status', 0);
                            })->where('permit_status', 0), // Hanya yang belum ditolak
                            'approved' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                    ->where('approvers.approver_status', 1);
                            }),
                            'rejected' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                    ->where('approvers.approver_status', -1);
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

                        if (!$userRole || !isset($data['value']) || strtolower($userRole->role_name) === 'administrator') {
                            return $query;
                        }

                        return match ($data['value']) {
                            'my_turn' => $query->where(function ($mainQuery) use ($userRole) {
                                $mainQuery->whereHas('approvers', function ($q) use ($userRole) {
                                    $q->where('approvers.role_id', $userRole->role_id)
                                        ->where('approvers.approver_status', 0);
                                });

                                $userRoleName = strtolower($userRole->role_name);
                                if ($userRoleName === 'staff perencanaan pengadaan') {
                                    // Staff perencanaan selalu bisa approve jika belum approve
                                    return;
                                } elseif ($userRoleName === 'staff it network') {
                                    // Staff IT hanya bisa jika staff perencanaan sudah approve
                                    $mainQuery->whereNotExists(function ($staffQuery) {
                                        $staffQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) = ?', ['staff perencanaan pengadaan'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'asisten manajer network') {
                                    // Asisten hanya bisa jika staff perencanaan dan staff it sudah approve
                                    $mainQuery->whereNotExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) IN (?, ?)', ['staff perencanaan pengadaan', 'staff it network'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'infra manager') {
                                    // Infra manager hanya bisa jika semua sebelumnya sudah approve
                                    $mainQuery->whereNotExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) IN (?, ?, ?)', [
                                                'staff perencanaan pengadaan',
                                                'staff it network',
                                                'asisten manajer network'
                                            ])
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

                                $userRoleName = strtolower($userRole->role_name);
                                if ($userRoleName === 'staff perencanaan pengadaan') {
                                    // Staff perencanaan tidak pernah menunggu
                                    $mainQuery->whereRaw('1 = 0');
                                } elseif ($userRoleName === 'staff it network') {
                                    // Staff IT menunggu staff perencanaan
                                    $mainQuery->whereExists(function ($staffQuery) {
                                        $staffQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) = ?', ['staff perencanaan pengadaan'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'asisten manajer network') {
                                    // Asisten menunggu staff perencanaan atau staff it
                                    $mainQuery->whereExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) IN (?, ?)', ['staff perencanaan pengadaan', 'staff it network'])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                } elseif ($userRoleName === 'infra manager') {
                                    // Infra manager menunggu semua sebelumnya
                                    $mainQuery->whereExists(function ($prevQuery) {
                                        $prevQuery->select('*')
                                            ->from('approvers')
                                            ->join('roles', 'approvers.role_id', '=', 'roles.role_id')
                                            ->whereRaw('LOWER(roles.role_name) IN (?, ?, ?)', [
                                                'staff perencanaan pengadaan',
                                                'staff it network',
                                                'asisten manajer network'
                                            ])
                                            ->whereColumn('approvers.permit_id', 'permits.permit_id')
                                            ->where('approvers.approver_status', 0);
                                    });
                                }
                            }),
                            'completed' => $query->whereHas('approvers', function ($q) use ($userRole) {
                                $q->where('approvers.role_id', $userRole->role_id)
                                    ->where('approvers.approver_status', 1);
                            }),
                            default => $query
                        };
                    }),
                Tables\Filters\SelectFilter::make('substation_documents')
                    ->label('Status Dokumen Substation')
                    ->options([
                        'has_documents' => 'Ada Dokumen',
                        'no_documents' => 'Belum Ada Dokumen',
                        'few_documents' => 'Dokumen Terbatas (< 3)',
                        'many_documents' => 'Dokumen Lengkap (≥ 3)'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['value']) || $data['value'] === '') {
                            return $query;
                        }

                        return match ($data['value']) {
                            'has_documents' => $query->whereHas('substations.documents'),
                            'no_documents' => $query->whereDoesntHave('substations.documents'),
                            'few_documents' => $query->whereHas('substations', function ($q) {
                                $q->withCount('documents')
                                    ->having('documents_count', '<', 3)
                                    ->having('documents_count', '>', 0);
                            }),
                            'many_documents' => $query->whereHas('substations', function ($q) {
                                $q->withCount('documents')
                                    ->having('documents_count', '>=', 3);
                            }),
                            default => $query
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_substation_documents')
                    ->label('Lihat Dokumen')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->visible(fn(?Permit $record) => $record?->substations?->documents?->count() > 0)
                    ->url(fn(?Permit $record) => $record?->substations ?
                        \App\Filament\Resources\SubstationResource::getUrl('view_documents', [
                            'record' => $record->substations,
                            'from' => 'permit'
                        ]) : null)
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make('approver_status')
                    ->label('Approve')
                    ->action(function (?Permit $record) {
                        if (!$record) return;

                        $userRole = Auth::user()->role;

                        if ($userRole && self::canUserApprove($record, $userRole)) {
                            DB::beginTransaction();

                            try {
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
                                    $record->update(['permit_status' => 1]);

                                    // Kirim notifikasi ke pembuat permit
                                    PermitNotificationService::notifyPermitCreator($record, 'approved');

                                    Log::info('Permit fully approved', [
                                        'permit_id' => $record->permit_id,
                                        'final_approver' => $userRole->role_name,
                                        'approved_by_user' => Auth::user()->name,
                                        'approved_at' => now()
                                    ]);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Permit Disetujui Sepenuhnya')
                                        ->body('Permit telah disetujui oleh semua approver dan siap diproses.')
                                        ->success()
                                        ->send();
                                } else {
                                    // Kirim notifikasi ke approver berikutnya
                                    PermitNotificationService::notifyNextApprover($record);

                                    Log::info('Permit partially approved', [
                                        'permit_id' => $record->permit_id,
                                        'approver' => $userRole->role_name,
                                        'approved_by_user' => Auth::user()->name,
                                        'progress' => "$approvedCount/$totalApprovers",
                                        'approved_at' => now()
                                    ]);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Approval Berhasil')
                                        ->body("Permit telah Anda setujui. Progress: $approvedCount/$totalApprovers approver.")
                                        ->success()
                                        ->send();
                                }

                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();

                                Log::error('Failed to approve permit', [
                                    'permit_id' => $record->permit_id,
                                    'user' => Auth::user()->name,
                                    'error' => $e->getMessage()
                                ]);

                                \Filament\Notifications\Notification::make()
                                    ->title('Gagal Menyetujui Permit')
                                    ->body('Terjadi kesalahan saat menyetujui permit. Silakan coba lagi.')
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak Dapat Menyetujui')
                                ->body('Anda tidak memiliki izin untuk menyetujui permit ini atau bukan giliran Anda.')
                                ->warning()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalDescription('Apakah Anda yakin ingin menyetujui permit ini? Approval akan dilakukan sesuai urutan yang telah ditentukan.')
                    ->color('success')
                    ->visible(function (?Permit $record) {
                        if (!$record) return false;

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
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->action(function (?Permit $record, array $data) {
                        if (!$record) return;

                        $userRole = Auth::user()->role;

                        if ($userRole && self::canUserApprove($record, $userRole)) {
                            DB::beginTransaction();

                            try {
                                // Update pivot record untuk role yang user miliki dengan status rejection (-1)
                                $record->approvers()
                                    ->updateExistingPivot($userRole->role_id, [
                                        'approver_status' => -1, // -1 untuk rejected
                                        'approved_at' => now(),
                                        'rejection_reason' => $data['rejection_reason'] ?? null
                                    ]);

                                // Update permit status menjadi rejected
                                $record->update([
                                    'permit_status' => -1, // -1 untuk rejected
                                    'rejected_by' => $userRole->role_name,
                                    'rejected_at' => now()
                                ]);

                                DB::commit();

                                // Kirim notifikasi ke pembuat permit dengan alasan penolakan
                                PermitNotificationService::notifyPermitCreator($record, 'rejected', $data['rejection_reason'] ?? null);

                                // Log activity
                                Log::info('Permit rejected', [
                                    'permit_id' => $record->permit_id,
                                    'rejected_by' => $userRole->role_name,
                                    'rejected_by_user' => Auth::user()->name,
                                    'rejection_reason' => $data['rejection_reason'] ?? 'No reason provided',
                                    'rejected_at' => now()
                                ]);

                                // Notifikasi success
                                \Filament\Notifications\Notification::make()
                                    ->title('Permit Berhasil Ditolak')
                                    ->body('Permit telah ditolak dan tidak dapat diproses lebih lanjut.')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                DB::rollback();

                                // Log error
                                Log::error('Failed to reject permit', [
                                    'permit_id' => $record->permit_id,
                                    'user' => Auth::user()->name,
                                    'error' => $e->getMessage()
                                ]);

                                // Notifikasi error
                                \Filament\Notifications\Notification::make()
                                    ->title('Gagal Menolak Permit')
                                    ->body('Terjadi kesalahan saat menolak permit. Silakan coba lagi.')
                                    ->danger()
                                    ->send();
                            }
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak Dapat Menolak')
                                ->body('Anda tidak memiliki izin untuk menolak permit ini atau bukan giliran Anda.')
                                ->warning()
                                ->send();
                        }
                    })
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan mengapa permit ini ditolak...')
                            ->required()
                            ->maxLength(500)
                            ->rows(4)
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Permit')
                    ->modalDescription('Apakah Anda yakin ingin menolak permit ini? Permit yang ditolak tidak dapat diproses lebih lanjut.')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(function (?Permit $record) {
                        if (!$record) return false;

                        $userRole = Auth::user()->role;

                        if (!$userRole) {
                            return false;
                        }

                        // Hanya tampil jika permit belum disetujui dan belum ditolak
                        if ($record->getAttribute('permit_status') != 0) {
                            return false;
                        }

                        // Cek apakah user memiliki role yang ada di approvers list
                        $hasApprovalRole = $record->approvers()
                            ->where('approvers.role_id', $userRole->role_id)
                            ->where('approvers.approver_status', 0)
                            ->exists();

                        // Dan cek apakah sesuai urutan approval
                        return $hasApprovalRole && self::canUserApprove($record, $userRole);
                    }),

                Tables\Actions\Action::make('reset_approvals')
                    ->label('Reset Approval')
                    ->action(function (?Permit $record) {
                        if (!$record) return;

                        if (self::resetApprovals($record)) {
                            // Kirim notifikasi ke semua approver
                            PermitNotificationService::notifyApproversOnReset($record);

                            \Filament\Notifications\Notification::make()
                                ->title('Reset Berhasil')
                                ->body('Semua approval telah direset. Permit dapat diproses ulang dari awal.')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Reset Gagal')
                                ->body('Terjadi kesalahan saat reset approval. Silakan coba lagi.')
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reset Approval Permit')
                    ->modalDescription('Apakah Anda yakin ingin mereset semua approval? Proses approval akan dimulai dari awal.')
                    ->modalSubmitActionLabel('Ya, Reset')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(function (?Permit $record) {
                        if (!$record) return false;

                        $userRole = Auth::user()->role;

                        // Hanya admin yang bisa reset
                        if (!$userRole || strtolower($userRole->role_name) !== 'administrator') {
                            return false;
                        }

                        // Hanya tampil jika permit sudah pernah diproses (approved/rejected)
                        return $record->getAttribute('permit_status') != 0;
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

        if (!$user) {
            return parent::getEloquentQuery()->where('permit_id', 0); // Return empty result if no user
        }

        // Ambil ID user - gunakan attribute yang tersedia
        $userId = $user->id ?? $user->user_id ?? null;

        if (!$userId) {
            return parent::getEloquentQuery()->where('permit_id', 0);
        }

        // Cek apakah user memiliki role Administrator
        $userRole = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.role_id')
            ->where('users.user_id', $userId)
            ->select('roles.role_name', 'roles.role_id')
            ->first();

        // Administrator bisa melihat SEMUA permit tanpa batasan apapun
        if ($userRole && strtolower($userRole->role_name) === 'administrator') {
            return parent::getEloquentQuery(); // Tampilkan semua permit
        }

        // Untuk user selain Administrator, filter berdasarkan permission
        return parent::getEloquentQuery()
            ->where(function ($query) use ($userId, $userRole) {
                // Tampilkan permit yang dibuat oleh user sendiri
                $query->where('user_id', $userId);

                // ATAU permit dimana user adalah approver (berdasarkan role)
                if ($userRole) {
                    $query->orWhereHas('approvers', function ($subQuery) use ($userRole) {
                        $subQuery->where('approvers.role_id', $userRole->role_id);
                    });
                }
            });
    }
}
