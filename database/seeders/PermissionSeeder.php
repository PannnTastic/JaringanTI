<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $permissions = [
            [
                'permission_name' => 'dashboard',
                'permission_label' => 'Dashboard',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'users',
                'permission_label' => 'Kelola User',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'roles',
                'permission_label' => 'Kelola Role',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'vendors',
                'permission_label' => 'Kelola Vendor',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'budgets',
                'permission_label' => 'Kelola Anggaran',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'gardus',
                'permission_label' => 'Kelola Gardu',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'pops',
                'permission_label' => 'Kelola POP',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
            [
                'permission_name' => 'documents',
                'permission_label' => 'Kelola Dokumen',
                'permission_type' => 'menu',
                'permission_status' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['permission_name' => $permission['permission_name']],
                $permission
            );
        }
    }
}
