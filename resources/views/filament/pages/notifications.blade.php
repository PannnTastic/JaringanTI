<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header dengan statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-primary-600">
                        {{ \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\\Models\\User')->where('notifiable_id', \Illuminate\Support\Facades\Auth::id())->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Total Notifications</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-warning-600">
                        {{ \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\\Models\\User')->where('notifiable_id', \Illuminate\Support\Facades\Auth::id())->whereNull('read_at')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Unread</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-success-600">
                        {{ \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\\Models\\User')->where('notifiable_id', \Illuminate\Support\Facades\Auth::id())->whereNotNull('read_at')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Read</div>
                </div>
            </x-filament::card>
        </div>

        <!-- Tabel notifikasi -->
        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
