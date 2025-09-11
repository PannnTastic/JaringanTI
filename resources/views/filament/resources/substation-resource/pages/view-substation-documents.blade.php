<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Substation Information Card -->
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="fi-section-header-ctn flex flex-col gap-3 mb-6">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Informasi Substation
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <div class="flex items-center gap-3 justify-between">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Nama Substation
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->substation_name ?? 'Tidak ada nama' }}
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
                                        POP
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->pops->pop_name ?? 'Tidak ada POP' }}
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
                                        User Pengelola
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
                                        Total Dokumen
                                    </span>
                                </label>
                            </div>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $record->documents->count() }} dokumen
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
                                        Dibuat Tanggal
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
                </div>
            </div>
        </div>

        <!-- Documents List -->
        @if($record->documents->isEmpty())
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-950 dark:text-white">Belum ada dokumen</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Substation ini belum memiliki dokumen yang diupload.</p>
                </div>
            </div>
        </div>
        @else
        @foreach($record->documents as $document)
        <div class="fi-section-content-ctn bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-content p-6">
                <!-- Document Header -->
                <div class="fi-section-header-ctn flex flex-col gap-3 mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ $document->doc_name }}
                        </h3>
                        <div class="flex items-center gap-2">
                            @if($document->fileExists())
                            <a href="{{ $document->file_url }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Document Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Nama File
                                </span>
                            </label>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ basename($document->doc_file) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Tipe File
                                </span>
                            </label>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ strtoupper(pathinfo($document->doc_file, PATHINFO_EXTENSION)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Diupload Oleh
                                </span>
                            </label>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $document->user->name ?? 'Unknown' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Tanggal Upload
                                </span>
                            </label>
                            <div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 ring-inset ring-gray-950/10 dark:ring-white/20">
                                <div class="min-w-0 flex-1 px-3 py-2">
                                    <span class="block w-full text-base text-gray-950 dark:text-white">
                                        {{ $document->created_at ? $document->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Preview -->
                @if($document->fileExists())
                @php
                    $fileExtension = strtolower(pathinfo($document->doc_file, PATHINFO_EXTENSION));
                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']);
                    $isPdf = $fileExtension === 'pdf';
                    $isWord = in_array($fileExtension, ['doc', 'docx']);
                    $isExcel = in_array($fileExtension, ['xls', 'xlsx']);
                    $isPowerPoint = in_array($fileExtension, ['ppt', 'pptx']);
                    $isVisio = in_array($fileExtension, ['vsd', 'vsdx']);
                @endphp
                
                @if($isImage)
                    <!-- Image Preview -->
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview Gambar
                                </span>
                            </label>
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ $document->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Lihat Full Size
                                    </a>
                                </div>
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-50">
                                    <img src="{{ $document->file_url }}" 
                                         alt="{{ $document->doc_name }}" 
                                         class="max-w-full h-auto mx-auto block"
                                         style="max-height: 500px; object-fit: contain;"
                                         onload="this.parentElement.classList.remove('bg-gray-50')"
                                         onerror="handleImageError(this)">
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($isPdf)
                    <!-- PDF Preview -->
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview PDF
                                </span>
                            </label>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button onclick="refreshPdfFrame('{{ $document->doc_id }}')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Refresh
                                        </button>
                                        <a href="{{ $document->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Buka di Tab Baru
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                                    <div id="pdf-loading-{{ $document->doc_id }}" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                                        <div class="text-center">
                                            <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Memuat PDF...</p>
                                        </div>
                                    </div>
                                    <iframe id="pdf-frame-{{ $document->doc_id }}"
                                            src="{{ $document->file_url }}#toolbar=1&navpanes=1&scrollbar=1" 
                                            class="w-full h-96 border-0"
                                            onload="document.getElementById('pdf-loading-{{ $document->doc_id }}').style.display='none'; this.style.display='block';"
                                            onerror="handlePdfError(this, '{{ $document->doc_id }}')"
                                            style="display: none;">
                                    </iframe>
                                </div>
                                
                                <div id="pdf-error-{{ $document->doc_id }}" style="display: none;" class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                Browser tidak dapat menampilkan PDF. 
                                                <a href="{{ $document->file_url }}" target="_blank" class="font-medium underline hover:text-yellow-900">
                                                    Klik di sini untuk membuka PDF di tab baru
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($isWord || $isExcel || $isPowerPoint)
                    <!-- Office Documents Preview -->
                    <div class="fi-fo-field-wrp">
                        <div class="grid gap-2">
                            <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Preview {{ strtoupper($fileExtension) }}
                                </span>
                            </label>
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="refreshOfficeFrame('{{ $document->doc_id }}')" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Refresh
                                    </button>
                                    <a href="{{ $document->file_url }}" target="_blank" class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-primary-600 border border-transparent rounded-md hover:bg-primary-700">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                                
                                <div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden relative">
                                    <div id="office-loading-{{ $document->doc_id }}" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
                                        <div class="text-center">
                                            <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">Memuat {{ strtoupper($fileExtension) }}...</p>
                                        </div>
                                    </div>
                                    <iframe id="office-frame-{{ $document->doc_id }}"
                                            src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($document->file_url) }}"
                                            class="w-full h-96 border-0"
                                            onload="document.getElementById('office-loading-{{ $document->doc_id }}').style.display='none'; this.style.display='block';"
                                            onerror="handleOfficeError(this, '{{ $document->doc_id }}')"
                                            style="display: none;">
                                    </iframe>
                                </div>
                                
                                <div id="office-error-{{ $document->doc_id }}" style="display: none;" class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                Microsoft Office Online tidak dapat memuat file. 
                                                <a href="{{ $document->file_url }}" target="_blank" class="font-medium underline hover:text-yellow-900">
                                                    Download file untuk membuka dengan aplikasi Office
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Other Files -->
                    <div class="fi-fo-field-wrp">
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
                                    <p class="mt-2 text-sm text-gray-950 dark:text-white">
                                        File {{ strtoupper($fileExtension) }} tidak dapat di-preview.<br>
                                        <a href="{{ $document->file_url }}" 
                                           target="_blank" 
                                           class="text-primary-600 hover:text-primary-500 font-medium">
                                            Klik di sini untuk membuka file
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @else
                <div class="fi-fo-field-wrp">
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">File tidak ditemukan</h3>
                                <p class="mt-1 text-sm text-red-700">File ini mungkin telah dihapus atau dipindahkan dari storage.</p>
                                <p class="mt-1 text-xs text-red-600">Path: {{ $document->doc_file }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
        @endif
    </div>
</x-filament-panels::page>

<script>
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

function handlePdfError(iframe, docId) {
    console.error('PDF loading error');
    document.getElementById('pdf-loading-' + docId).style.display = 'none';
    document.getElementById('pdf-error-' + docId).style.display = 'block';
    iframe.style.display = 'none';
}

function handleOfficeError(iframe, docId) {
    console.error('Office document loading error');
    document.getElementById('office-loading-' + docId).style.display = 'none';
    document.getElementById('office-error-' + docId).style.display = 'block';
    iframe.style.display = 'none';
}

function refreshPdfFrame(docId) {
    const iframe = document.getElementById('pdf-frame-' + docId);
    const loading = document.getElementById('pdf-loading-' + docId);
    const error = document.getElementById('pdf-error-' + docId);
    
    loading.style.display = 'flex';
    error.style.display = 'none';
    iframe.style.display = 'none';
    
    const currentSrc = iframe.src;
    iframe.src = '';
    setTimeout(() => {
        iframe.src = currentSrc;
    }, 100);
}

function refreshOfficeFrame(docId) {
    const iframe = document.getElementById('office-frame-' + docId);
    const loading = document.getElementById('office-loading-' + docId);
    const error = document.getElementById('office-error-' + docId);
    
    loading.style.display = 'flex';
    error.style.display = 'none';
    iframe.style.display = 'none';
    
    const currentSrc = iframe.src;
    iframe.src = '';
    setTimeout(() => {
        iframe.src = currentSrc;
    }, 100);
}

// Auto-hide loading after timeout for each document
document.addEventListener('DOMContentLoaded', function() {
    // Get all PDF frames
    const pdfFrames = document.querySelectorAll('[id^="pdf-frame-"]');
    pdfFrames.forEach(iframe => {
        const docId = iframe.id.replace('pdf-frame-', '');
        setTimeout(() => {
            const loading = document.getElementById('pdf-loading-' + docId);
            if (loading && loading.style.display !== 'none') {
                loading.style.display = 'none';
                iframe.style.display = 'block';
                setTimeout(() => {
                    if (iframe.contentDocument === null) {
                        handlePdfError(iframe, docId);
                    }
                }, 5000);
            }
        }, 3000);
    });
    
    // Get all Office frames
    const officeFrames = document.querySelectorAll('[id^="office-frame-"]');
    officeFrames.forEach(iframe => {
        const docId = iframe.id.replace('office-frame-', '');
        setTimeout(() => {
            const loading = document.getElementById('office-loading-' + docId);
            if (loading && loading.style.display !== 'none') {
                loading.style.display = 'none';
                iframe.style.display = 'block';
                setTimeout(() => {
                    if (iframe.contentDocument === null) {
                        handleOfficeError(iframe, docId);
                    }
                }, 8000);
            }
        }, 5000);
    });
});
</script>
