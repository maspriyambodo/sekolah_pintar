<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysMenu;
use App\Models\System\SysPermission;
use App\Models\System\SysRole;
use App\Models\System\SysRolePermission;
use App\Models\System\SysUser;
use App\Models\System\SysUserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class RbacSeeder extends Seeder
{
    /**
     * Load data from JSON file
     */
    private function loadJsonData(string $filename): array
    {
        $jsonPath = database_path('seeders/json/' . $filename);
        
        if (!File::exists($jsonPath)) {
            $this->command->warn("Warning: {$filename} file not found!");
            return [];
        }

        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        if (!isset($data['RECORDS']) || !is_array($data['RECORDS'])) {
            $this->command->warn("Warning: Invalid JSON format in {$filename}!");
            return [];
        }

        return $data['RECORDS'];
    }

    public function run(): void
    {
        // Seed from JSON files
        $this->seedPermissions();
        $this->seedRoles();
        $this->seedMenus();
        $this->seedUserRoles();
        $this->seedRolePermissions();
        $this->seedUsers();

        $this->command->info('RBAC seeding completed from JSON files!');
    }

    private function seedPermissions(): void
    {
        $records = $this->loadJsonData('sys_permissions.json');
        
        if (empty($records)) {
            $this->command->info('No permissions data to seed');
            return;
        }

        foreach ($records as $p) {
            SysPermission::firstOrCreate(
                ['code' => $p['code']],
                [
                    'code' => $p['code'],
                    'name' => $p['name'],
                    'module' => $p['module'],
                ]
            );
        }

        $this->command->info('Permissions seeded from JSON!');
    }

    private function seedRoles(): void
    {
        $records = $this->loadJsonData('sys_roles.json');
        
        if (empty($records)) {
            $this->command->info('No roles data to seed');
            return;
        }

        foreach ($records as $r) {
            SysRole::firstOrCreate(
                ['code' => $r['code']],
                [
                    'code' => $r['code'],
                    'name' => $r['name'],
                    'description' => $r['description'] ?? null,
                ]
            );
        }

        $this->command->info('Roles seeded from JSON!');
    }

    private function seedMenus(): void
    {
        $records = $this->loadJsonData('sys_menus.json');
        
        if (empty($records)) {
            $this->command->info('No menus data to seed');
            return;
        }

        foreach ($records as $m) {
            // Find or create parent
            $parentId = null;
            if (!empty($m['parent_id'])) {
                $parent = SysMenu::where('nama_menu', $m['parent_menu'] ?? '')->first();
                $parentId = $parent?->id;
            }

            // Find permission
            $permissionId = null;
            if (!empty($m['permission_code'])) {
                $permission = SysPermission::where('code', $m['permission_code'])->first();
                $permissionId = $permission?->id;
            }

            SysMenu::firstOrCreate(
                ['nama_menu' => $m['nama_menu'], 'url' => $m['url']],
                [
                    'nama_menu' => $m['nama_menu'],
                    'url' => $m['url'],
                    'icon' => $m['icon'] ?? null,
                    'urutan' => $m['urutan'] ?? 0,
                    'is_active' => $m['is_active'] ?? true,
                    'parent_id' => $parentId,
                    'sys_permission_id' => $permissionId,
                ]
            );
        }

        $this->command->info('Menus seeded from JSON!');
    }

    private function seedUserRoles(): void
    {
        $records = $this->loadJsonData('sys_user_roles.json');
        
        if (empty($records)) {
            $this->command->info('No user roles data to seed');
            return;
        }

        foreach ($records as $ur) {
            // Find user and role
            $user = SysUser::find($ur['sys_user_id']);
            $role = SysRole::find($ur['sys_role_id']);

            if ($user && $role) {
                SysUserRole::firstOrCreate(
                    ['sys_user_id' => $ur['sys_user_id'], 'sys_role_id' => $ur['sys_role_id']],
                    [
                        'sys_user_id' => $ur['sys_user_id'],
                        'sys_role_id' => $ur['sys_role_id'],
                    ]
                );
            }
        }

        $this->command->info('User roles seeded from JSON!');
    }

    private function seedRolePermissions(): void
    {
        $records = $this->loadJsonData('sys_role_permissions.json');
        
        if (empty($records)) {
            $this->command->info('No role permissions data to seed');
            return;
        }

        foreach ($records as $rp) {
            SysRolePermission::firstOrCreate(
                ['sys_role_id' => $rp['sys_role_id'], 'sys_permission_id' => $rp['sys_permission_id']],
                [
                    'sys_role_id' => $rp['sys_role_id'],
                    'sys_permission_id' => $rp['sys_permission_id'],
                ]
            );
        }

        $this->command->info('Role permissions seeded from JSON!');
    }

    private function seedUsers(): void
    {
        $records = $this->loadJsonData('sys_users.json');
        
        if (empty($records)) {
            $this->command->info('No users data to seed');
            return;
        }

        foreach ($records as $u) {
            SysUser::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => $u['password'],
                    'role' => $u['role'] ?? 1,
                    'is_active' => $u['is_active'] ?? 1,
                ]
            );
        }

        $this->command->info('Users seeded from JSON!');
    }
}
