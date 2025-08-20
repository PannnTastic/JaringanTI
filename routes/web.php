<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

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
    
    return view('kb', compact('categories', 'allKnowledge', 'apps'));
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


Route::get('/kb/{kb:kb_id}', function ($kbId) {
    $kb = \App\Models\Knowledgebase::with('category')->findOrFail($kbId);
    return view('single-kb', compact('kb'));
})->name('single-kb');





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

require __DIR__.'/auth.php';
