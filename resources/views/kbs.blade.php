@include('partials.head')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knowledge Base Per Kategori - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root { 
            color-scheme: light only !important; 
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
        }
        
        html, body { 
            background: var(--gray-50) !important; 
            color: var(--gray-900) !important; 
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-600);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-700);
        }
        
        /* Line clamp utilities */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Card animations */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0px);
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Sidebar animations */
        .sidebar-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-link:hover {
            border-left-color: var(--primary);
            background: linear-gradient(90deg, var(--primary-light) 0%, rgba(59, 130, 246, 0.05) 100%);
        }
        
        .sidebar-link.active {
            border-left-color: var(--primary);
            background: linear-gradient(90deg, var(--primary-light) 0%, rgba(59, 130, 246, 0.1) 100%);
            color: var(--primary-dark);
        }
        
        /* Badge animations */
        .badge {
            transition: all 0.2s ease;
        }
        
        .badge:hover {
            transform: scale(1.05);
        }
        
        /* Button hover effects */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1e3a8a 100%);
            transform: translateY(-1px);
        }
        
        /* Mobile sidebar */
        .mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-sidebar.open {
            transform: translateX(0);
        }
        
        /* Header gradient */
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Prose styling for modal content */
        .prose {
            max-width: none;
        }
        
        .prose h1, .prose h2, .prose h3 {
            color: var(--gray-900);
            font-weight: 600;
        }
        
        .prose p {
            color: var(--gray-700);
            line-height: 1.7;
        }
        
        .prose a {
            color: var(--primary);
            text-decoration: none;
        }
        
        .prose a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Search input styling */
        .search-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Grid responsive improvements */
        @media (min-width: 768px) {
            .grid-responsive {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            }
        }
        
        /* Mobile improvements */
        @media (max-width: 768px) {
            .mobile-padding {
                padding: 1rem;
            }
            
            .mobile-text {
                font-size: 0.875rem;
            }
        }
        
        /* Focus states for accessibility */
        .focus-ring:focus {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Mobile Header -->
    <div class="md:hidden bg-white border-b p-4 flex items-center justify-between sticky top-0 z-40">
        <h1 class="text-lg font-bold text-gray-900">Knowledge Base</h1>
        <button id="mobileMenuBtn" class="p-2 text-gray-600 hover:text-gray-900 focus-ring rounded-md">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden md:hidden"></div>

    <div class="min-h-screen flex">
        <!-- Desktop Sidebar -->
        <aside class="w-64 bg-white border-r hidden md:block sticky top-0 h-screen overflow-y-auto">
            <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-layer-group text-blue-600 text-md"></i>
                    </div>
                    Kategori
                </h2>
                <p class="text-sm text-gray-600 mt-1">Pilih kategori untuk melihat artikel</p>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="/" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-700 font-medium hover:text-gray-900 focus-ring">
                            <i class="fas fa-arrow-left mr-3 text-gray-500"></i>
                            <span>Kembali ke Beranda</span>
                        </a>
                    </li>
                    <li class="pt-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-2">Kategori</div>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('kbs', $cat->field_id) }}" class="sidebar-link flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 hover:text-gray-900 focus-ring {{ request()->route('field') == $cat->field_id ? 'active font-semibold' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-folder mr-3 text-blue-500"></i>
                                <span class="truncate ml-1 max-w-[10rem]">{{ \Illuminate\Support\Str::limit($cat->field_name, 13) }}</span>
                            </div>
                            <span class="badge ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                {{ $cat->knowledgebase->count() }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobileSidebar" class="mobile-sidebar fixed inset-y-0 left-0 w-64 bg-white border-r z-50 md:hidden overflow-y-auto">
            <div class="p-4 border-b flex items-center justify-between bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-md flex items-center justify-center mr-2">
                        <i class="fas fa-layer-group text-white text-xs"></i>
                    </div>
                    Kategori
                </h2>
                <button id="closeMobileMenu" class="p-1 text-gray-600 hover:text-gray-900 focus-ring rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="/" class="sidebar-link flex items-center px-3 py-2 rounded-lg text-gray-700 font-medium hover:text-gray-900 focus-ring">
                            <i class="fas fa-arrow-left mr-3 text-gray-500"></i>
                            <span>Kembali</span>
                        </a>
                    </li>
                    <li class="pt-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-1">Kategori</div>
                    </li>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('kbs', $cat->field_id) }}" class="sidebar-link flex items-center justify-between px-3 py-2 rounded-lg text-gray-700 hover:text-gray-900 focus-ring overflow-hidden {{ request()->route('field') == $cat->field_id ? 'active font-semibold' : '' }}">
                            <div class="flex items-center min-w-0">
                                <i class="fas fa-folder mr-3 text-blue-500 flex-shrink-0"></i>
                                <span class="truncate ml-1 max-w-[10rem]">{{ \Illuminate\Support\Str::limit($cat->field_name, 13) }}</span>
                            </div>
                            <span class="badge ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">
                                {{ $cat->knowledgebase->count() }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-hidden max-w-full">
            <!-- Header Section -->
            <div class="bg-white border-b px-6 py-8 hidden md:block">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                            </div>
                            Knowledge Base
                            @if(isset($categories) && request()->route('field'))
                                @php $current = $categories->firstWhere('field_id', request()->route('field')); @endphp
                                @if($current)
                                    <span class="text-blue-600 ml-2">- {{ $current->field_name }}</span>
                                @endif
                            @endif
                        </h1>
                        <p class="text-gray-600 mt-2">
                            @if(isset($current))
                                Menampilkan {{ $allKnowledge->count() }} artikel dalam kategori {{ $current->field_name }}
                            @else
                                Jelajahi koleksi pengetahuan dan artikel terkurasi
                            @endif
                        </p>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="relative max-w-md w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari artikel..." 
                               class="search-input w-full pl-10 pr-4 py-2 rounded-lg focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 hidden md:grid">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-400">Total Artikel</p>
                                <p class="text-2xl font-bold text-gray-600">{{ $allKnowledge->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-400">Kategori Aktif</p>
                                <p class="text-2xl font-bold text-gray-600">{{ $categories->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tags text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-400">Kontributor</p>
                                <p class="text-2xl font-bold text-gray-600">{{ $allKnowledge->pluck('user.name')->unique()->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div id="articlesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 grid-responsive auto-rows-fr">
                    @forelse($allKnowledge as $kb)
                    <article class="bg-white rounded-xl shadow-sm border border-gray-200 card-hover p-6 flex flex-col h-full overflow-hidden group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center bg-gray-200 ">
                                    <span class="text-blue-400 font-bold text-md">{{ substr($kb->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <span class="badge inline-block px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        {{ $kb->user->name }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 flex-shrink-0">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $kb->created_at ? $kb->created_at->format('d M Y') : '' }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2 overflow-hidden group-hover:text-blue-600 transition-colors">
                            {{ $kb->kb_name }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 overflow-hidden flex-grow leading-relaxed">
                            {{ Str::limit(strip_tags($kb->kb_content), 140) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>
                                <span>{{ ceil(str_word_count(strip_tags($kb->kb_content)) / 200) }} min read</span>
                            </div>
                            <a href="{{ route('single-kb', $kb->kb_id) }}" 
                               class="inline-flex items-center text-blue-600 text-sm font-medium hover:text-blue-700 transition-colors focus-ring rounded-md px-2 py-1">
                                Baca selengkapnya 
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </article>
                    @empty
                    <div class="col-span-full">
                        <div class="text-center text-gray-500 py-16 bg-white rounded-xl border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-search text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada artikel</h3>
                            <p class="text-gray-500">Tidak ada artikel pada kategori ini saat ini.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <!-- Enhanced Modal -->
    <div id="articleModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl transform transition-all">
                <div class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-gray-50 to-white">
                    <h2 id="modalTitle" class="text-2xl font-bold text-gray-900 pr-8"></h2>
                    <button onclick="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus-ring">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto max-h-[70vh]">
                    <div id="modalContent" class="prose max-w-none"></div>
                </div>
                <div class="flex justify-end p-6 border-t bg-gray-50 space-x-3">
                    <button onclick="closeModal()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors focus-ring">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
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
        
        // Modal event listeners
        document.getElementById('articleModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        function openMobileMenu() {
            mobileSidebar.classList.add('open');
            mobileOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMobileMenuFn() {
            mobileSidebar.classList.remove('open');
            mobileOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }
        
        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', closeMobileMenuFn);
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', closeMobileMenuFn);
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const articles = document.querySelectorAll('#articlesGrid article');
                
                articles.forEach(article => {
                    const title = article.querySelector('h3').textContent.toLowerCase();
                    const content = article.querySelector('p').textContent.toLowerCase();
                    const author = article.querySelector('.badge').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || content.includes(searchTerm) || author.includes(searchTerm)) {
                        article.style.display = 'flex';
                    } else {
                        article.style.display = 'none';
                    }
                });
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading states for article links
        document.querySelectorAll('a[href*="single-kb"]').forEach(link => {
            link.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';
            });
        });
    </script>
</body>
</html>
