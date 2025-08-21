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
                {!! $kb->kb_content !!}
            </div>
        </div>
    </div>
</body>
</html>
