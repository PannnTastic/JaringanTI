<?php

use App\Models\Role;
use App\Models\User;
use App\Services\LdapAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a default role for testing
    $this->defaultRole = Role::factory()->create(['role_id' => 1, 'role_name' => 'User']);

    // Mock LDAP configuration for testing
    Config::set('auth.ldap.enabled', true);
    Config::set('auth.ldap.server', 'ldap.test.com');
    Config::set('auth.ldap.base_dn', 'dc=test,dc=com');
    Config::set('auth.ldap.user_dn', 'uid={username},ou=users,dc=test,dc=com');
    Config::set('auth.ldap.domain', 'test.com');
    Config::set('auth.ldap.auto_create_users', true);
    Config::set('auth.ldap.default_role_id', 1);
});

test('can authenticate existing database user with email', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'role_id' => $this->defaultRole->role_id,
    ]);

    $credentials = [
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    expect(Auth::attempt($credentials))->toBeTrue();
    expect(Auth::user()->user_id)->toBe($user->user_id);
});

test('can authenticate existing database user with username', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'role_id' => $this->defaultRole->role_id,
    ]);

    $credentials = [
        'username' => 'testuser',
        'password' => 'password123',
    ];

    expect(Auth::attempt($credentials))->toBeTrue();
    expect(Auth::user()->user_id)->toBe($user->user_id);
});

test('ldap service handles missing configuration gracefully', function () {
    Config::set('auth.ldap.enabled', false);

    $ldapService = new LdapAuthService;

    expect($ldapService->authenticate('testuser', 'password'))->toBeFalse();
});

test('user model supports ldap type checking', function () {
    $ldapUser = User::factory()->create([
        'user_type' => 'ldap',
        'role_id' => $this->defaultRole->role_id,
    ]);

    $regularUser = User::factory()->create([
        'user_type' => null,
        'role_id' => $this->defaultRole->role_id,
    ]);

    expect($ldapUser->isLdapUser())->toBeTrue();
    expect($regularUser->isLdapUser())->toBeFalse();
});

test('authentication fails with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'role_id' => $this->defaultRole->role_id,
    ]);

    $credentials = [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ];

    expect(Auth::attempt($credentials))->toBeFalse();
    expect(Auth::user())->toBeNull();
});

test('can login to filament with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'role_id' => $this->defaultRole->role_id,
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'password123',
    ]);

    expect($response->status())->toBe(302);
    $this->assertAuthenticatedAs($user);
});

test('filament login fails with invalid credentials', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
        'role_id' => $this->defaultRole->role_id,
    ]);

    $response = $this->post('/admin/login', [
        'email' => 'admin@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['data.email']);
    $this->assertGuest();
});
