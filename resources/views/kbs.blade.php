@include('partials.head')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base Per Kategori - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { color-scheme: light only !important; }
        html, body { background: #f9fafb !important; color: #111827 !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex">
        <!-- Sidebar Kategori -->
        <aside class="w-64 bg-white border-r p-6 hidden md:block">
            <h2 class="text-2xl font-bold mb-4">
                <i class="fas fa-tags mr-2 text-blue-600"></i>Kategori
            </h2>
            <ul class="space-y-5 " >
                <li>
                    <a href="/" class="block px-3 py-2 rounded hover:bg-blue-50 text-gray-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('kbs', $cat->field_id) }}" class="block px-3 py-2 rounded hover:bg-blue-50 text-gray-700 {{ request()->route('field') == $cat->field_id ? 'bg-blue-100 font-bold' : '' }}">
                        <i class="fas fa-folder mr-2 text-blue-600"></i> {{ $cat->field_name }}
                        <span class="ml-2 text-xs text-gray-500">({{ $cat->knowledgebase->count() }})</span>
                    </a>
                </li>
                    
                @endforeach
            </ul>
        </aside>
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Knowledge Base
                @if(isset($categories) && request()->route('field'))
                    @php $current = $categories->firstWhere('field_id', request()->route('field')); @endphp
                    @if($current)
                        <span class="text-blue-600">- {{ $current->field_name }}</span>
                    @endif
                @endif
            </h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($allKnowledge as $kb)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                            {{ $kb->user->name}}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $kb->created_at ? $kb->created_at->format('d M Y') : '' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $kb->kb_name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit(strip_tags($kb->kb_content), 120) }}</p>
                    <a href="{{ route('single-kb', $kb->kb_id) }}" class="text-blue-600 text-sm font-medium">
                        Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <i class="fas fa-search text-4xl mb-2"></i>
                    <div>Tidak ada artikel pada kategori ini.</div>
                </div>
                @endforelse
            </div>
        </main>
    </div>
    <!-- Modal -->
    <div id="articleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <div id="modalContent" class="prose max-w-none"></div>
                </div>
                <div class="flex justify-end p-6 border-t bg-gray-50">
                    <button onclick="closeModal()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModal(id, title, content) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('articleModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal() {
            document.getElementById('articleModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        document.getElementById('articleModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>
</html>
