<div class="space-y-4">
    {{-- Header Information --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ $title }}
            </h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $status === 'Read' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                {{ $status }}
            </span>
        </div>
        
        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Received: {{ $createdAt->format('d/m/Y H:i') }}
            @if($readAt)
                | Read: {{ $readAt->format('d/m/Y H:i') }}
            @endif
        </div>
    </div>

    {{-- Message Body --}}
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Message</h4>
        <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $body }}</div>
    </div>

    {{-- Permit Information --}}
    @if($permitId)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Permit Information
        </h4>
        <p class="text-blue-800 dark:text-blue-200">Permit ID: #{{ $permitId }}</p>
        
        {{-- Rejection Reason Section --}}
        @if($rejectionReason)
        <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded">
            <h5 class="text-sm font-medium text-red-900 dark:text-red-100 mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Rejection Reason
            </h5>
            <p class="text-red-800 dark:text-red-200 text-sm whitespace-pre-wrap">{{ $rejectionReason }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Notification Type Badge --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500 dark:text-gray-400">Type:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @switch($type)
                    @case('success')
                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                        @break
                    @case('danger')
                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                        @break
                    @case('warning')
                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                        @break
                    @default
                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                @endswitch">
                {{ ucfirst($type) }}
            </span>
        </div>
        
        @if($permitId)
        <a href="{{ route('filament.admin.resources.permits.index') }}?tableSearch={{ $permitId }}" 
           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:text-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            View Permit Details
        </a>
        @endif
    </div>
</div>
