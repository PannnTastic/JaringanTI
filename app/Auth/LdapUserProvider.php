<?php

namespace App\Auth;

use App\Models\User;
use App\Services\LdapAuthService;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

class LdapUserProvider extends EloquentUserProvider
{
    protected LdapAuthService $ldapService;

    public function __construct($hasher, $model, LdapAuthService $ldapService)
    {
        parent::__construct($hasher, $model);
        $this->ldapService = $ldapService;
    }

    /**
     * Validate a user against the given credentials.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (! $user instanceof User) {
            return false;
        }

        $username = $credentials['username'] ?? $credentials['email'] ?? '';
        $password = $credentials['password'] ?? '';

        // For LDAP users or if LDAP is forced, try LDAP authentication first
        if ($user->user_type === 'ldap' || config('auth.ldap.force_ldap', false)) {
            if ($this->ldapService->authenticate($username, $password)) {
                Log::info("LDAP authentication successful for user: {$user->email}");

                return true;
            }
        }

        // Fallback to database authentication for local users
        if (! empty($user->password)) {
            $isValid = $this->hasher->check($password, $user->password);
            if ($isValid) {
                Log::info("Database authentication successful for user: {$user->email}");
            }

            return $isValid;
        }

        Log::warning("Authentication failed for user: {$user->email}");

        return false;
    }

    /**
     * Retrieve a user by the given credentials.
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (empty($credentials) ||
           (count($credentials) === 1 && array_key_exists('password', $credentials))) {
            return null;
        }

        $login = $credentials['username'] ?? $credentials['email'] ?? '';

        if (empty($login)) {
            return null;
        }

        // Search for user by email or username
        $user = $this->createModel()->newQuery()
            ->where(function ($query) use ($login) {
                $query->where('email', $login)
                    ->orWhere('username', $login);
            })
            ->first();

        // If user not found and LDAP auto-create is enabled, try to create from LDAP
        if (! $user && config('auth.ldap.auto_create_users', false)) {
            $password = $credentials['password'] ?? '';
            if (! empty($password)) {
                $user = $this->ldapService->syncUser($login, $password);
                if ($user) {
                    Log::info("Auto-created LDAP user: {$user->email}");
                }
            }
        }

        return $user;
    }
}
