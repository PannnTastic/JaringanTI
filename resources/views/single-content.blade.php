@include('partials.head')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konten - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body { background: #f9fafb !important; color: #111827 !important; }
        #content-detail img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #content-detail figure {
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
            <div class="mb-4 flex items-center justify-between">
                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                    {{ $content->user->name ?? 'Umum' }}
                </span>
                <span class="ml-4 text-xs text-gray-500">
                    {{ $content->created_at ? $content->created_at->format('d M Y') : '' }}
                </span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $content->content_title }}</h1>
            <div class="mb-6 text-center">
                @if($content->content_photo)
                    <img src="{{ asset('admin/storage/' . $content->content_photo) }}" alt="Foto Konten" class="rounded-lg shadow max-h-96 mx-auto">
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
                        <div class="text-sm text-gray-500 mt-2">Ukuran file: {{ $human }}</div>
                    @endif
                @endif
            </div>
            <div id="content-detail" class="prose prose-lg max-w-none">
                {!! nl2br(e($content->content_description)) !!}
            </div>
        </div>
    </div>
</body>
</html>
