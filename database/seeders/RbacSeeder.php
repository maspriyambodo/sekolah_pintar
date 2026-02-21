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
use Illuminate\Support\Facades\DB;

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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Seed from JSON files
        $this->seedPermissions();
        $this->seedRoles();
        $this->seedMenus();
        $this->seedUserRoles();
        $this->seedRolePermissions();
        $this->seedUsers();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            SysMenu::firstOrCreate(
                ['id' => $m['id']],
                [
                    'parent_id' => $m['parent_id'] ?? null,
                    'sys_permission_id' => $m['sys_permission_id'],
                    'nama_menu' => $m['nama_menu'],
                    'url' => $m['url'] ?? null,
                    'icon' => $m['icon'] ?? null,
                    'urutan' => $m['urutan'] ?? null,
                    'is_active' => $m['is_active'] ?? 1,
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
            SysUserRole::firstOrCreate(
                ['sys_user_id' => $ur['sys_user_id'], 'sys_role_id' => $ur['sys_role_id']],
                [
                    'sys_user_id' => $ur['sys_user_id'],
                    'sys_role_id' => $ur['sys_role_id'],
                ]
            );
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
                    'email' => $u['email'],
                    'name' => $u['name'],
                    'password' => $u['password'],
                    'role' => $u['role'],
                    'is_active' => $u['is_active'] ?? 1,
                ]
            );
        }

        $this->command->info('Users seeded from JSON!');
    }
}
