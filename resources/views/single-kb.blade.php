@include('partials.head')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kb->kb_name ?? 'Artikel' }} - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { color-scheme: light only !important; }
        html, body { background: #f9fafb !important; color: #111827 !important; }
        
        /* Styling untuk konten Trix */
        .attachment {
            margin: 1rem 0;
        }
        
        .attachment img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .attachment__caption {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
            font-style: italic;
        }
        
        /* Styling untuk gambar dalam konten */
        #kb-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Styling untuk figure */
        #kb-content figure {
            margin: 1.5rem 0;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto py-10 px-4">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-blue-600 hover:underline mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <div class="bg-white rounded-lg shadow p-8">
            <div class="mb-4">
                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                    {{ $kb->user->name ?? 'Umum' }}
                </span>
                <span class="ml-4 text-xs text-gray-500">
                    {{ $kb->created_at ? $kb->created_at->format('d M Y') : '' }}
                </span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $kb->kb_name }}</h1>
            <div class="prose max-w-none text-gray-800">
                <div id="kb-content">
                    {!! $kb->processed_content ?? $kb->kb_content ?? 'Tidak ada content tersedia.' !!}
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk handle broken images --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle broken images
            const images = document.querySelectorAll('#kb-content img');
            images.forEach(function(img) {
                img.onerror = function() {
                    // Replace with placeholder atau hide
                    this.style.display = 'none';
                    
                    // Atau ganti dengan placeholder
                    // this.src = '/img/placeholder.png';
                    // this.alt = 'Gambar tidak tersedia';
                    
                    // Tampilkan pesan error
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-50 border border-red-200 rounded-md p-4 my-4';
                    errorDiv.innerHTML = '<div class="flex"><div class="flex-shrink-0"><i class="fas fa-exclamation-triangle text-red-400"></i></div><div class="ml-3"><h3 class="text-sm font-medium text-red-800">Gambar tidak dapat dimuat</h3><p class="text-sm text-red-700 mt-1">File gambar mungkin sudah dipindahkan atau dihapus.</p></div></div>';
                    this.parentNode.insertBefore(errorDiv, this.nextSibling);
                };
                
                // Force check if image loads
                if (img.complete && img.naturalHeight === 0) {
                    img.onerror();
                }
            });
        });
    </script>
</body>
</html>
