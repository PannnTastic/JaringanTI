<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;

// Route::redirect('/', 'admin');

// route untuk halaman knowledge berdasarkan kategori
Route::get('/', function () {
    $categories = \App\Models\Field::with(['knowledgebase' => function($query) {
        $query->where('kb_status', 1)->orderBy('created_at', 'desc');
    }])->get();
    
    $allKnowledge = \App\Models\Knowledgebase::with('category')
        ->where('kb_status', 1)
        ->orderBy('created_at', 'desc')
        ->get();
    $apps = \App\Models\App::all(); // Ambil semua aplikasi
    // Ambil hingga 7 foto random dari contents untuk slideshow
    $slides = \App\Models\Content::whereNotNull('content_photo')
        ->inRandomOrder()
        ->limit(7)
        ->get();
    
    return view('kb', compact('categories', 'allKnowledge', 'apps', 'slides'));
});

Route::get('/categories/{field:field_id}', function ($fieldId) {
    $categories = \App\Models\Field::with(['knowledgebase' => function($query) {
        $query->where('kb_status', 1)->orderBy('created_at', 'desc');
    }])->get();
    
    $allKnowledge = \App\Models\Knowledgebase::with('category')
        ->where('kb_status', 1)
        ->where('field_id', $fieldId)
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('kbs', compact('categories', 'allKnowledge'));
})->name('kbs');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'proses_login'])->name('proses_login');
Route::get('/kb/{kb:kb_id}', function ($kbId) {
    $kb = \App\Models\Knowledgebase::with('category')->findOrFail($kbId);
    
    // Process content to validate image paths and provide fallbacks
    $kb->processed_content = preg_replace_callback(
        '/<img[^>]*src=["\']([^"\']*)["\'][^>]*>/i',
        function ($matches) {
            $fullMatch = $matches[0];
            $imagePath = $matches[1];
            
            // Convert storage URL to file path for checking
            if (str_starts_with($imagePath, '/storage/')) {
                $filePath = public_path(str_replace('/storage/', 'storage/', $imagePath));
            } else {
                $filePath = public_path($imagePath);
            }
            
            // If file doesn't exist, add data attribute for JS handling
            if (!file_exists($filePath)) {
                // Add data attributes to help with error handling
                if (strpos($fullMatch, 'data-missing="true"') === false) {
                    $fullMatch = str_replace('<img', '<img data-missing="true" data-original-src="' . htmlspecialchars($imagePath) . '"', $fullMatch);
                }
            }
            
            return $fullMatch;
        },
        $kb->kb_content ?? ''
    );
    
    return view('single-kb', compact('kb'));
})->name('single-kb');

Route::get('/content/{content:content_id}', function ($contentId) {
    $content = \App\Models\Content::with('user')->findOrFail($contentId);
    return view('single-content', compact('content'));
})->name('single-content');

// Route untuk menampilkan semua konten
Route::get('/contents', function () {
    $contents = \App\Models\Content::with('user')
        ->orderBy('created_at', 'desc')
        ->get();
    return view('contents', compact('contents'));
})->name('contents');

Route::get('/user', [UserController::class, 'index'])->name('users');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('livewire.settings.profile');
    })->name('profile');
});

// Route untuk serving file storage ketika web server tidak bisa akses langsung
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404, 'File not found');
    }
    
    $mimeType = mime_content_type($filePath);
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
    ]);
})->where('path', '.*')->name('storage.local');

require __DIR__.'/auth.php';
