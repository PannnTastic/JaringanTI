<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::redirect('/', 'admin');

Route::get('/', function () {
    $categories = \App\Models\Knowledgebase_category::with(['knowledgebase' => function($query) {
        $query->where('kb_status', 1)->orderBy('created_at', 'desc');
    }])->get();
    
    $allKnowledge = \App\Models\Knowledgebase::with('category')
        ->where('kb_status', 1)
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('kb', compact('categories', 'allKnowledge'));
});

// Route::get('/admin', function () {
//     return view('kb');
// });




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
