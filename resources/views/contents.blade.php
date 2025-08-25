@include('partials.head')
@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Konten - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body { background: #f9fafb !important; color: #111827 !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        .break-all { word-break: break-all; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        <main class="max-w-6xl mx-auto p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Semua Konten</h1>
                <a href="/" class="text-sm text-blue-600 hover:underline"><i class="fas fa-home mr-2"></i>Beranda</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-fr">
                @forelse($contents as $content)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow p-6 flex flex-col h-full overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full truncate max-w-32">
                            {{ $content->user->name ?? 'Umum' }}
                        </span>
                        <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                            {{ $content->created_at ? $content->created_at->format('d M Y') : '' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 break-all">{{ $content->content_title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow break-all">{{ Str::limit(strip_tags($content->content_description), 120) }}</p>
                    @if($content->content_photo)
                        <img src="{{ asset('storage/' . $content->content_photo) }}" alt="Foto Konten" class="w-full h-40 object-cover rounded-md mb-4">
                        @php
                            $size = null;
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($content->content_photo)) {
                                    $size = \Illuminate\Support\Facades\Storage::disk('public')->size($content->content_photo);
                                }
                            } catch (Exception $e) {
                                $size = null;
                            }

                            if ($size) {
                                $units = ['B','KB','MB','GB','TB'];
                                $power = $size > 0 ? floor(log($size, 1024)) : 0;
                                $human = number_format($size / pow(1024, $power), ($power ? 2 : 0)) . ' ' . $units[$power];
                            }
                        @endphp
                        @if(!empty($human))
                            <div class="text-xs text-gray-500 mt-1">Ukuran: {{ $human }}</div>
                        @endif
                    @endif
                    <a href="{{ route('single-content', $content->content_id) }}" class="text-blue-600 text-sm font-medium mt-auto hover:text-blue-700 transition-colors">
                        Lihat detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <i class="fas fa-search text-4xl mb-2"></i>
                    <div>Tidak ada konten tersedia.</div>
                </div>
                @endforelse
            </div>
        </main>
    </div>
</body>
</html>
