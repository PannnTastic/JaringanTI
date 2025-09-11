<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use LdapRecord\Connection;

class LdapAuthService
{
    protected Connection $connection;
    protected string $server;
    protected int $port;
    protected string $baseDn;
    protected string $domain;

    public function __construct()
    {
        $this->server = config('auth.ldap.server', 'batamdc1.plnbatam.com');
        $this->port = config('auth.ldap.port', 389);
        $this->baseDn = config('auth.ldap.base_dn', 'dc=plnbatam,dc=com');
        $this->domain = config('auth.ldap.domain', 'plnbatam.com');
        
        // Initialize LdapRecord connection
        $this->connection = new Connection([
            'hosts' => [$this->server],
            'username' => '',
            'password' => '',
            'port' => $this->port,
            'base_dn' => $this->baseDn,
            'timeout' => 5,
        ]);
    }

    /**
     * Authenticate user with LDAP using LdapRecord (supports email and username)
     */
    public function authenticate(string $login, string $password): bool
    {
        if (!$this->isLdapEnabled()) {
            return false;
        }

        try {
            // Determine if login is email or username
            $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
            
            // For email login, use the email as-is
            // For username login, append domain
            $authUsername = $isEmail ? $login : $login . '@' . $this->domain;
            
            // Create authenticated connection using user credentials
            $authConnection = new Connection([
                'hosts' => [$this->server],
                'username' => $authUsername,
                'password' => $password,
                'port' => $this->port,
                'base_dn' => $this->baseDn,
                'timeout' => 5,
            ]);

            // Try to connect and authenticate
            $authConnection->connect();
            
            // Extract username for search (remove domain if email)
            $searchUsername = $isEmail ? explode('@', $login)[0] : $login;
            
            // Test the connection by doing a simple search
            if ($isEmail) {
                // Search by email
                $results = $authConnection->query()
                    ->select(['samaccountname', 'mail'])
                    ->where('mail', '=', $login)
                    ->limit(1)
                    ->get();
            } else {
                // Search by username
                $results = $authConnection->query()
                    ->select(['samaccountname', 'mail'])
                    ->where('samaccountname', '=', $searchUsername)
                    ->limit(1)
                    ->get();
            }

            if (!empty($results) && count($results) > 0) {
                Log::info("LDAP: User {$login} authenticated successfully (method: " . ($isEmail ? 'email' : 'username') . ")");
                return true;
            }

            Log::warning("LDAP: User {$login} not found after authentication");
            return false;

        } catch (Exception $e) {
            Log::error('LDAP Authentication Error for ' . $login . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user details from LDAP using authenticated connection (supports email and username)
     */
    public function getUserDetails(string $login, string $password = null): ?array
    {
        if (!$this->isLdapEnabled()) {
            return null;
        }

        try {
            // Use authenticated connection if password provided
            if ($password) {
                // Determine if login is email or username
                $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
                $authUsername = $isEmail ? $login : $login . '@' . $this->domain;
                
                $connection = new Connection([
                    'hosts' => [$this->server],
                    'username' => $authUsername,
                    'password' => $password,
                    'port' => $this->port,
                    'base_dn' => $this->baseDn,
                    'timeout' => 5,
                ]);
                $connection->connect();
            } else {
                $connection = $this->connection;
                $connection->connect();
            }

            // Determine search strategy
            $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);
            $searchUsername = $isEmail ? explode('@', $login)[0] : $login;

            // Search for user by email or username
            if ($isEmail) {
                $results = $connection->query()
                    ->select(['displayname', 'mail', 'samaccountname', 'department', 'title', 'objectguid'])
                    ->where('mail', '=', $login)
                    ->get();
            } else {
                $results = $connection->query()
                    ->select(['displayname', 'mail', 'samaccountname', 'department', 'title', 'objectguid'])
                    ->where('samaccountname', '=', $searchUsername)
                    ->get();
            }

            if (empty($results) || count($results) === 0) {
                Log::warning("LDAP: User {$login} not found");
                return null;
            }

            // Get first user from results
            $ldapUser = is_array($results) ? $results[0] : $results->first();

            // Helper function to get attribute value
            $getAttribute = function($user, $attr) {
                if (is_array($user) && isset($user[$attr])) {
                    return is_array($user[$attr]) ? $user[$attr][0] : $user[$attr];
                }
                if (is_object($user) && method_exists($user, 'getFirstAttribute')) {
                    return $user->getFirstAttribute($attr);
                }
                return null;
            };

            return [
                'name' => $getAttribute($ldapUser, 'displayname') ?: $searchUsername,
                'email' => $getAttribute($ldapUser, 'mail') ?: $searchUsername.'@'.$this->domain,
                'username' => $searchUsername,
                'department' => $getAttribute($ldapUser, 'department'),
                'title' => $getAttribute($ldapUser, 'title'),
                'guid' => $getAttribute($ldapUser, 'objectguid'),
            ];

        } catch (Exception $e) {
            Log::error('LDAP Get User Details Error: '.$e->getMessage());
            return null;
        }
    }

    /**
     * Create or update user from LDAP data (based on your existing implementation)
     */
    public function syncUser(string $username, string $password): ?User
    {
        if (!$this->authenticate($username, $password)) {
            return null;
        }

        $ldapUserData = $this->getUserDetails($username, $password);
        if (!$ldapUserData) {
            return null;
        }

        try {
            // Check if user exists by username or email
            $user = User::where('username', $username)
                ->orWhere('email', $ldapUserData['email'])
                ->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $ldapUserData['name'],
                    'email' => $ldapUserData['email'],
                    'username' => $username,
                    'password' => Hash::make($password), // Random password for LDAP users
                    'email_verified_at' => now(),
                    'role_id' => config('auth.ldap.default_role_id', 1),
                    'user_type' => 'Pegawai', // Use enum value from database
                    'user_status' => true,
                    'guid' => $ldapUserData['guid'],
                    'domain' => $this->domain,
                ]);

                Log::info("LDAP: Created new user {$user->email} from LDAP");
            } else {
                // Update existing user with LDAP data
                $user->update([
                    'name' => $ldapUserData['name'],
                    'email' => $ldapUserData['email'],
                    'username' => $username,
                    'user_type' => 'Pegawai', // Use enum value
                    'email_verified_at' => now(),
                    'guid' => $ldapUserData['guid'],
                    'domain' => $this->domain,
                ]);

                Log::info("LDAP: Updated existing user {$user->email} from LDAP");
            }

            return $user;

        } catch (Exception $e) {
            Log::error('LDAP Sync User Error: '.$e->getMessage());
            return null;
        }
    }

    /**
     * Check if LDAP is enabled and properly configured
     */
    protected function isLdapEnabled(): bool
    {
        return config('auth.ldap.enabled', false)
            && ! empty($this->server)
            && ! empty($this->baseDn);
    }
}
