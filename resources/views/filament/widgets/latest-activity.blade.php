<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aktivitas Terbaru
        </x-slot>

        <div class="space-y-3">
            @forelse ($this->getViewData()['activities'] as $activity)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg dark:bg-gray-800">
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $activity->type === 'User' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' }}">
                            {{ $activity->type }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->description }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $activity->created_at->diffForHumans() }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
