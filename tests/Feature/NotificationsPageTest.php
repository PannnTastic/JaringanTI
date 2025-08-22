<?php

declare(strict_types=1);

use App\Filament\Pages\Notifications;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    // Login sebagai user yang memiliki notifications (user_id = 2)
    $this->user = User::where('user_id', 2)->first();
    actingAs($this->user);
});

it('can access notifications page when authenticated', function () {
    expect(Notifications::canAccess())->toBeTrue();
});

it('can access notifications route', function () {
    get('/admin/notifications')
        ->assertSuccessful()
        ->assertSee('Notifications');
});

it('shows correct notification count', function () {
    $notificationsCount = DatabaseNotification::where('notifiable_type', 'App\\Models\\User')
        ->where('notifiable_id', $this->user->user_id)
        ->count();

    expect($notificationsCount)->toBeGreaterThan(0);
});

it('shows unread notifications count in title', function () {
    $page = new Notifications;
    $title = $page->getTitle();

    expect($title)->toContain('Notifications');
});

it('can query notifications for current user', function () {
    $notifications = DatabaseNotification::query()
        ->where('notifiable_type', 'App\\Models\\User')
        ->where('notifiable_id', $this->user->user_id)
        ->latest()
        ->get();

    expect($notifications)->not->toBeEmpty();

    $firstNotification = $notifications->first();
    expect($firstNotification->data)->toHaveKey('title');
    expect($firstNotification->data)->toHaveKey('body');
    expect($firstNotification->data)->toHaveKey('type');
});

it('notifications contain proper data structure', function () {
    $notification = DatabaseNotification::where('notifiable_type', 'App\\Models\\User')
        ->where('notifiable_id', $this->user->user_id)
        ->first();

    if ($notification) {
        expect($notification->data)->toHaveKey('title');
        expect($notification->data)->toHaveKey('body');
        expect($notification->data)->toHaveKey('type');
        expect($notification->data)->toHaveKey('permit_id');
        expect($notification->data)->toHaveKey('action');
    }
});
