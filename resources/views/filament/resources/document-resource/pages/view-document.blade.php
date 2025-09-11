<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Document Information Card -->
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="fi-section-header-ctn flex flex-col gap-3 mb-6">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Informasi Dokumen
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Nama Dokumen
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->doc_name ?: 'Tidak ada nama dokumen' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Status
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $record->doc_status ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }}">
                                        {{ $record->doc_status ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Nama User
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->user->name ?? 'Tidak ada user' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Substation
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->substations->substation_name ?? 'Tidak ada substation' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Tanggal Dibuat
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->created_at ? $record->created_at->format('d/m/Y H:i') : 'Tidak ada tanggal' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Terakhir Diupdate
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->updated_at ? $record->updated_at->format('d/m/Y H:i') : 'Tidak ada tanggal' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Preview Card -->
        @if($record->doc_file)
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="fi-section-header-ctn flex flex-col gap-3 mb-6">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Preview File
                    </h3>
                </div>
                
                <div class="space-y-4">
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Nama File
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ basename($record->doc_file) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Ekstensi File
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ strtoupper(pathinfo($record->doc_file, PATHINFO_EXTENSION)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                
                @php
                    $fileExtension = strtolower(pathinfo($record->doc_file, PATHINFO_EXTENSION));
                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                    $isPdf = $fileExtension === 'pdf';
                    $isOfficeDoc = in_array($fileExtension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
                    $isVisio = in_array($fileExtension, ['vsd', 'vsdx']);
                    $isText = in_array($fileExtension, ['txt', 'csv']);
                    
                    // Generate Office Online Viewer URL
                    $officeViewerUrl = null;
                    if ($isOfficeDoc || $isPdf) {
                        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($record->file_url);
                    }
                    
                    // Generate Google Docs Viewer URL as fallback
                    $googleViewerUrl = 'https://docs.google.com/gview?url=' . urlencode($record->file_url) . '&embedded=true';
                @endphp
                
                @if($isImage)
                    <!-- Image Preview -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview Gambar
                                </span>
                            </label>
                            @if($record->fileExists())
                            <div class="space-y-3">
                                <!-- Image Controls -->
                                <div class="flex items-center gap-2">
                                    <a href="{{ $record->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Lihat Full Size
                                    </a>
                                </div>
                                
                                <!-- Image Preview -->
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50">
                                    <img src="{{ $record->file_url }}" 
                                         alt="{{ $record->doc_name }}" 
                                         class="max-w-full h-auto mx-auto block"
                                         style="max-height: 500px; object-fit: contain;"
                                         onload="this.parentElement.classList.remove('bg-gray-50')"
                                         onerror="handleImageError(this)">
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M34 40H14a4 4 0 01-4-4V12a4 4 0 014-4h20a4 4 0 014 4v24a4 4 0 01-4 4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M14 28l6-6 4 4 8-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="20.5" cy="16.5" r="1.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-2">File gambar tidak ditemukan di storage</p>
                                    <p class="text-sm text-gray-400">Path: {{ $record->doc_file }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($isPdf)
                    <!-- PDF Preview -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview PDF
                                </span>
                            </label>
                            @if($record->fileExists())
                            <div class="space-y-4">
                                <!-- PDF Controls -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button onclick="refreshPdfFrame()" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Refresh
                                        </button>
                                        <a href="{{ $record->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Buka di Tab Baru
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- PDF Preview Container -->
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                                    <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                                        <div class="text-center">
                                            <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Memuat PDF...</p>
                                        </div>
                                    </div>
                                    <iframe id="pdf-frame"
                                            src="{{ $record->file_url }}#toolbar=1&navpanes=1&scrollbar=1" 
                                            class="w-full h-96 border-0"
                                            onload="document.getElementById('pdf-loading').style.display='none'; this.style.display='block';"
                                            onerror="handlePdfError(this)"
                                            style="display: none;">
                                    </iframe>
                                </div>
                                
                                <!-- Fallback message -->
                                <div id="pdf-error" style="display: none;" class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                Browser tidak dapat menampilkan PDF. 
                                                <a href="{{ $record->file_url }}" target="_blank" class="font-medium underline hover:text-yellow-900">
                                                    Klik di sini untuk membuka PDF di tab baru
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m6 0h6m-6 6v6m0 0v6m0-6h6m-6 0H9"/>
                                    </svg>
                                    <p class="mt-2">File PDF tidak ditemukan di storage</p>
                                    <p class="text-sm text-gray-400">Path: {{ $record->doc_file }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($isOfficeDoc)
                    <!-- Office Documents (Word, Excel, PowerPoint) Preview -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview {{ strtoupper($fileExtension) }}
                                </span>
                            </label>
                            @if($record->fileExists())
                            <div class="space-y-4">
                                <!-- Office Controls -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button onclick="switchOfficeViewer('office')" id="btn-office" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            Microsoft Viewer
                                        </button>
                                        <button onclick="switchOfficeViewer('google')" id="btn-google" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                            Google Viewer
                                        </button>
                                        <a href="{{ $record->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Office Preview Container -->
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                                    <div id="office-loading" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                                        <div class="text-center">
                                            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Memuat dokumen...</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Microsoft Office Viewer -->
                                    <iframe id="office-frame"
                                            src="{{ $officeViewerUrl }}" 
                                            class="w-full h-96 border-0"
                                            onload="handleOfficeLoad(this, 'office')"
                                            onerror="handleOfficeError(this, 'office')"
                                            style="display: none;">
                                    </iframe>
                                    
                                    <!-- Google Docs Viewer -->
                                    <iframe id="google-frame"
                                            src="{{ $googleViewerUrl }}" 
                                            class="w-full h-96 border-0"
                                            onload="handleOfficeLoad(this, 'google')"
                                            onerror="handleOfficeError(this, 'google')"
                                            style="display: none;">
                                    </iframe>
                                </div>
                                
                                <!-- Fallback message -->
                                <div id="office-error" style="display: none;" class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                Dokumen tidak dapat di-preview secara online. 
                                                <a href="{{ $record->file_url }}" target="_blank" class="font-medium underline hover:text-yellow-900">
                                                    Klik di sini untuk mendownload file
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m6 0h6m-6 6v6m0 0v6m0-6h6m-6 0H9"/>
                                    </svg>
                                    <p class="mt-2">File {{ strtoupper($fileExtension) }} tidak ditemukan di storage</p>
                                    <p class="text-sm text-gray-400">Path: {{ $record->doc_file }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($isVisio)
                    <!-- Visio Files Preview -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview Visio ({{ strtoupper($fileExtension) }})
                                </span>
                            </label>
                            @if($record->fileExists())
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ $record->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Download Visio File
                                    </a>
                                    <span class="text-xs text-gray-500">Requires Microsoft Visio to open</span>
                                </div>
                                
                                <div class="rounded-lg border border-purple-200 bg-purple-50 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-purple-800">
                                                File Visio tidak dapat di-preview di browser. File ini memerlukan Microsoft Visio untuk dibuka.
                                            </p>
                                            <p class="text-xs text-purple-600 mt-1">
                                                Alternatif: Anda dapat menggunakan Visio Viewer atau mengkonversi ke format PDF untuk preview online.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m6 0h6m-6 6v6m0 0v6m0-6h6m-6 0H9"/>
                                    </svg>
                                    <p class="mt-2">File Visio tidak ditemukan di storage</p>
                                    <p class="text-sm text-gray-400">Path: {{ $record->doc_file }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($isText)
                    <!-- Text Files Preview -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview {{ strtoupper($fileExtension) }}
                                </span>
                            </label>
                            @if($record->fileExists())
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ $record->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Buka di Tab Baru
                                    </a>
                                </div>
                                
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <iframe src="{{ $record->file_url }}" 
                                            class="w-full h-96 border-0 bg-white">
                                    </iframe>
                                </div>
                            </div>
                            @else
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m6 0h6m-6 6v6m0 0v6m0-6h6m-6 0H9"/>
                                    </svg>
                                    <p class="mt-2">File {{ strtoupper($fileExtension) }} tidak ditemukan di storage</p>
                                    <p class="text-sm text-gray-400">Path: {{ $record->doc_file }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Other Files -->
                    <div class="mt-4 fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    File Preview
                                </span>
                            </label>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 p-6 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    @if($record->fileExists())
                                    <p class="mt-2 text-sm text-gray-950 dark:text-white">
                                        File {{ strtoupper($fileExtension) }} tidak dapat di-preview.<br>
                                        <a href="{{ $record->file_url }}" 
                                           target="_blank" 
                                           class="text-primary-600 hover:text-primary-500 font-medium">
                                            Klik di sini untuk membuka file
                                        </a>
                                    </p>
                                    @else
                                    <p class="mt-2 text-sm text-gray-500">
                                        File {{ strtoupper($fileExtension) }} tidak ditemukan di storage.<br>
                                        <span class="text-xs">Path: {{ $record->doc_file }}</span>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>
        @else
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-950 dark:text-white font-medium">Tidak ada file yang diupload</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Debug Information (Only in development) -->
        @if(config('app.debug'))
        <div class="fi-section-content-ctn bg-yellow-50 rounded-xl shadow-sm ring-1 ring-yellow-200 dark:bg-yellow-900/20 dark:ring-yellow-700/50 mt-6">
            <div class="fi-section-content p-4">
                <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-3">Debug Information</h3>
                <div class="space-y-2 text-xs text-yellow-700 dark:text-yellow-300">
                    <p><strong>File Path in DB:</strong> {{ $record->doc_file ?? 'NULL' }}</p>
                    <p><strong>File Exists:</strong> {{ $record->fileExists() ? 'Yes' : 'No' }}</p>
                    <p><strong>Generated URL:</strong> {{ $record->file_url ?? 'NULL' }}</p>
                    <p><strong>Storage Disk:</strong> {{ config('filesystems.default') }}</p>
                    <p><strong>Public URL:</strong> {{ config('filesystems.disks.public.url') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>

<script>
function handlePdfError(iframe) {
    console.error('PDF loading error');
    document.getElementById('pdf-loading').style.display = 'none';
    document.getElementById('pdf-error').style.display = 'block';
    iframe.style.display = 'none';
}

function handleImageError(img) {
    console.error('Image loading error:', img.src);
    img.parentElement.innerHTML = `
        <div class="p-8 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M34 40H14a4 4 0 01-4-4V12a4 4 0 014-4h20a4 4 0 014 4v24a4 4 0 01-4 4z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 28l6-6 4 4 8-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="20.5" cy="16.5" r="1.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="mt-2 font-medium">Gambar tidak dapat dimuat</p>
            <p class="text-sm text-gray-400 mt-1">File mungkin rusak atau tidak dapat diakses</p>
            <a href="${img.src}" target="_blank" class="inline-block mt-3 text-primary-600 hover:text-primary-500 text-sm font-medium">
                Coba buka di tab baru
            </a>
        </div>
    `;
}

let currentOfficeViewer = 'office';

function switchOfficeViewer(viewer) {
    const officeFrame = document.getElementById('office-frame');
    const googleFrame = document.getElementById('google-frame');
    const loading = document.getElementById('office-loading');
    const error = document.getElementById('office-error');
    const btnOffice = document.getElementById('btn-office');
    const btnGoogle = document.getElementById('btn-google');
    
    if (!officeFrame || !googleFrame) return;
    
    currentOfficeViewer = viewer;
    
    // Reset states
    loading.style.display = 'flex';
    error.style.display = 'none';
    officeFrame.style.display = 'none';
    googleFrame.style.display = 'none';
    
    // Update button states
    if (viewer === 'office') {
        btnOffice.className = btnOffice.className.replace('bg-white border-gray-300 text-gray-700 hover:bg-gray-50', 'bg-blue-600 border-transparent text-white hover:bg-blue-700');
        btnGoogle.className = btnGoogle.className.replace('bg-blue-600 border-transparent text-white hover:bg-blue-700', 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50');
        
        // Reload office frame
        const currentSrc = officeFrame.src;
        officeFrame.src = '';
        setTimeout(() => {
            officeFrame.src = currentSrc;
        }, 100);
    } else {
        btnGoogle.className = btnGoogle.className.replace('bg-white border-gray-300 text-gray-700 hover:bg-gray-50', 'bg-blue-600 border-transparent text-white hover:bg-blue-700');
        btnOffice.className = btnOffice.className.replace('bg-blue-600 border-transparent text-white hover:bg-blue-700', 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50');
        
        // Reload google frame
        const currentSrc = googleFrame.src;
        googleFrame.src = '';
        setTimeout(() => {
            googleFrame.src = currentSrc;
        }, 100);
    }
}

function handleOfficeLoad(iframe, viewer) {
    if (currentOfficeViewer === viewer) {
        const loading = document.getElementById('office-loading');
        const error = document.getElementById('office-error');
        
        if (loading) loading.style.display = 'none';
        if (error) error.style.display = 'none';
        iframe.style.display = 'block';
    }
}

function handleOfficeError(iframe, viewer) {
    console.error('Office document loading error:', iframe.src);
    
    if (currentOfficeViewer === viewer) {
        const loading = document.getElementById('office-loading');
        const error = document.getElementById('office-error');
        
        if (loading) loading.style.display = 'none';
        iframe.style.display = 'none';
        
        // Try switching to alternative viewer automatically
        if (viewer === 'office') {
            console.log('Office viewer failed, trying Google viewer...');
            setTimeout(() => switchOfficeViewer('google'), 1000);
        } else {
            // Both viewers failed, show error
            if (error) error.style.display = 'block';
        }
    }
}

function refreshPdfFrame() {
    const iframe = document.getElementById('pdf-frame');
    const loading = document.getElementById('pdf-loading');
    const error = document.getElementById('pdf-error');
    
    // Reset states
    loading.style.display = 'flex';
    error.style.display = 'none';
    iframe.style.display = 'none';
    
    // Reload iframe
    const currentSrc = iframe.src;
    iframe.src = '';
    setTimeout(() => {
        iframe.src = currentSrc;
    }, 100);
}

// Auto-hide loading after timeout for PDF
setTimeout(() => {
    const loading = document.getElementById('pdf-loading');
    const iframe = document.getElementById('pdf-frame');
    const error = document.getElementById('pdf-error');
    
    if (loading && loading.style.display !== 'none') {
        loading.style.display = 'none';
        if (iframe) {
            iframe.style.display = 'block';
        }
        // If still no content, show error after 5 seconds
        setTimeout(() => {
            if (iframe && iframe.contentDocument === null) {
                handlePdfError(iframe);
            }
        }, 5000);
    }
}, 3000);

// Auto-hide loading after timeout for Office documents
setTimeout(() => {
    const loading = document.getElementById('office-loading');
    const officeFrame = document.getElementById('office-frame');
    const googleFrame = document.getElementById('google-frame');
    
    if (loading && loading.style.display !== 'none') {
        loading.style.display = 'none';
        
        // Show appropriate frame based on current viewer
        if (currentOfficeViewer === 'office' && officeFrame) {
            officeFrame.style.display = 'block';
        } else if (currentOfficeViewer === 'google' && googleFrame) {
            googleFrame.style.display = 'block';
        }
        
        // Check if content loaded after additional timeout
        setTimeout(() => {
            const activeFrame = currentOfficeViewer === 'office' ? officeFrame : googleFrame;
            if (activeFrame && activeFrame.contentDocument === null) {
                handleOfficeError(activeFrame, currentOfficeViewer);
            }
        }, 5000);
    }
}, 4000); // Slightly longer timeout for Office docs
</script>
