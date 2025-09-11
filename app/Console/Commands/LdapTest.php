<?php

namespace App\Console\Commands;

use App\Services\LdapAuthService;
use Illuminate\Console\Command;

class LdapTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ldap:test {username?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test LDAP connection and authentication';

    protected LdapAuthService $ldapService;

    public function __construct(LdapAuthService $ldapService)
    {
        parent::__construct();
        $this->ldapService = $ldapService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Testing LDAP Configuration...');
        $this->newLine();

        // Check if LDAP extension is loaded
        if (! extension_loaded('ldap')) {
            $this->error('❌ LDAP extension is not loaded in PHP');

            return 1;
        }
        $this->info('✅ LDAP extension is loaded');

        // Check configuration
        $enabled = config('auth.ldap.enabled');
        $server = config('auth.ldap.server');
        $port = config('auth.ldap.port');
        $baseDn = config('auth.ldap.base_dn');

        $this->table(['Configuration', 'Value'], [
            ['LDAP Enabled', $enabled ? 'Yes' : 'No'],
            ['Server', $server],
            ['Port', $port],
            ['Base DN', $baseDn],
            ['User DN Template', config('auth.ldap.user_dn')],
            ['Domain', config('auth.ldap.domain')],
            ['Auto Create Users', config('auth.ldap.auto_create_users') ? 'Yes' : 'No'],
            ['Force LDAP', config('auth.ldap.force_ldap') ? 'Yes' : 'No'],
        ]);

        if (! $enabled) {
            $this->warn('⚠️  LDAP is disabled in configuration');

            return 1;
        }

        if (empty($server) || empty($baseDn)) {
            $this->error('❌ LDAP server or base DN is not configured');

            return 1;
        }

        // Test connection
        $this->info('🔗 Testing LDAP connection...');
        $connection = @ldap_connect($server, $port);

        if (! $connection) {
            $this->error("❌ Failed to connect to LDAP server {$server}:{$port}");

            return 1;
        }

        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

        $this->info("✅ Successfully connected to LDAP server {$server}:{$port}");

        // Test authentication if credentials provided
        $username = $this->argument('username');
        $password = $this->argument('password');

        if ($username && $password) {
            $this->newLine();
            $this->info("🔐 Testing authentication for user: {$username}");

            if ($this->ldapService->authenticate($username, $password)) {
                $this->info("✅ Authentication successful for {$username}");

                // Try to get user details
                $userDetails = $this->ldapService->getUserDetails($username);
                if ($userDetails) {
                    $this->info('📋 User Details:');
                    $this->table(['Field', 'Value'], [
                        ['Name', $userDetails['name']],
                        ['Email', $userDetails['email']],
                        ['Username', $userDetails['username']],
                        ['Department', $userDetails['department'] ?? 'N/A'],
                        ['Title', $userDetails['title'] ?? 'N/A'],
                    ]);
                }
            } else {
                $this->error("❌ Authentication failed for {$username}");
            }
        } else {
            $this->warn('💡 Provide username and password to test authentication:');
            $this->line('   php artisan ldap:test <username> <password>');
        }

        ldap_close($connection);

        $this->newLine();
        $this->info('🎉 LDAP test completed');

        return 0;
    }
}
