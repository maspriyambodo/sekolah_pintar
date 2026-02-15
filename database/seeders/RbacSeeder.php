<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysPermission;
use App\Models\System\SysRole;
use App\Models\System\SysUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Siswa permissions
            ['code' => 'siswa.view', 'name' => 'View Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.create', 'name' => 'Create Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.update', 'name' => 'Update Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.delete', 'name' => 'Delete Siswa', 'module' => 'siswa'],

            // Guru permissions
            ['code' => 'guru.view', 'name' => 'View Guru', 'module' => 'guru'],
            ['code' => 'guru.create', 'name' => 'Create Guru', 'module' => 'guru'],
            ['code' => 'guru.update', 'name' => 'Update Guru', 'module' => 'guru'],
            ['code' => 'guru.delete', 'name' => 'Delete Guru', 'module' => 'guru'],

            // Kelas permissions
            ['code' => 'kelas.view', 'name' => 'View Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.create', 'name' => 'Create Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.update', 'name' => 'Update Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.delete', 'name' => 'Delete Kelas', 'module' => 'kelas'],

            // Nilai permissions
            ['code' => 'nilai.view', 'name' => 'View Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.create', 'name' => 'Create Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.update', 'name' => 'Update Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.delete', 'name' => 'Delete Nilai', 'module' => 'nilai'],

            // Absensi permissions
            ['code' => 'absensi.view', 'name' => 'View Absensi', 'module' => 'absensi'],
            ['code' => 'absensi.create', 'name' => 'Create Absensi', 'module' => 'absensi'],
            ['code' => 'absensi.update', 'name' => 'Update Absensi', 'module' => 'absensi'],
            ['code' => 'absensi.delete', 'name' => 'Delete Absensi', 'module' => 'absensi'],

            // BK permissions
            ['code' => 'bk.view', 'name' => 'View Bimbingan Konseling', 'module' => 'bk'],
            ['code' => 'bk.create', 'name' => 'Create Bimbingan Konseling', 'module' => 'bk'],
            ['code' => 'bk.update', 'name' => 'Update Bimbingan Konseling', 'module' => 'bk'],
            ['code' => 'bk.delete', 'name' => 'Delete Bimbingan Konseling', 'module' => 'bk'],

            // Perpustakaan permissions
            ['code' => 'perpustakaan.view', 'name' => 'View Perpustakaan', 'module' => 'perpustakaan'],
            ['code' => 'perpustakaan.create', 'name' => 'Create Perpustakaan', 'module' => 'perpustakaan'],
            ['code' => 'perpustakaan.update', 'name' => 'Update Perpustakaan', 'module' => 'perpustakaan'],
            ['code' => 'perpustakaan.delete', 'name' => 'Delete Perpustakaan', 'module' => 'perpustakaan'],

            // User management permissions
            ['code' => 'users.view', 'name' => 'View Users', 'module' => 'users'],
            ['code' => 'users.create', 'name' => 'Create Users', 'module' => 'users'],
            ['code' => 'users.update', 'name' => 'Update Users', 'module' => 'users'],
            ['code' => 'users.delete', 'name' => 'Delete Users', 'module' => 'users'],

            // Role management permissions
            ['code' => 'roles.view', 'name' => 'View Roles', 'module' => 'roles'],
            ['code' => 'roles.create', 'name' => 'Create Roles', 'module' => 'roles'],
            ['code' => 'roles.update', 'name' => 'Update Roles', 'module' => 'roles'],
            ['code' => 'roles.delete', 'name' => 'Delete Roles', 'module' => 'roles'],
        ];

        foreach ($permissions as $permission) {
            SysPermission::firstOrCreate(
                ['code' => $permission['code']],
                $permission
            );
        }

        // Create roles
        $roles = [
            [
                'code' => 'admin',
                'name' => 'Administrator',
                'description' => 'Full access to all features',
            ],
            [
                'code' => 'guru',
                'name' => 'Guru',
                'description' => 'Teacher role with limited access',
            ],
            [
                'code' => 'siswa',
                'name' => 'Siswa',
                'description' => 'Student role with view-only access',
            ],
            [
                'code' => 'wali',
                'name' => 'Wali Murid',
                'description' => 'Parent role with limited access',
            ],
        ];

        foreach ($roles as $roleData) {
            SysRole::firstOrCreate(
                ['code' => $roleData['code']],
                $roleData
            );
        }

        // Assign permissions to roles
        $adminRole = SysRole::where('code', 'admin')->first();
        $guruRole = SysRole::where('code', 'guru')->first();
        $siswaRole = SysRole::where('code', 'siswa')->first();
        $waliRole = SysRole::where('code', 'wali')->first();

        // Admin gets all permissions
        $allPermissions = SysPermission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Guru permissions
        $guruPermissions = SysPermission::whereIn('module', [
            'siswa', 'guru', 'kelas', 'nilai', 'absensi', 'bk', 'perpustakaan'
        ])->whereIn('code', [
            'siswa.view', 'siswa.create', 'siswa.update',
            'guru.view',
            'kelas.view',
            'nilai.view', 'nilai.create', 'nilai.update',
            'absensi.view', 'absensi.create', 'absensi.update',
            'bk.view', 'bk.create', 'bk.update',
            'perpustakaan.view', 'perpustakaan.create', 'perpustakaan.update',
        ])->get();
        $guruRole->permissions()->sync($guruPermissions->pluck('id'));

        // Siswa permissions (view only their own data)
        $siswaPermissions = SysPermission::whereIn('code', [
            'siswa.view',
            'nilai.view',
            'absensi.view',
        ])->get();
        $siswaRole->permissions()->sync($siswaPermissions->pluck('id'));

        // Wali permissions (view related students)
        $waliPermissions = SysPermission::whereIn('code', [
            'siswa.view',
            'nilai.view',
            'absensi.view',
            'bk.view',
        ])->get();
        $waliRole->permissions()->sync($waliPermissions->pluck('id'));

        // Create default admin user
        $adminUser = SysUser::firstOrCreate(
            ['email' => 'admin@sekolah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 1, // Assuming '1' is the default role for admin
                'is_active' => true,
            ]
        );

        // Assign admin role
        $adminUser->roles()->sync([$adminRole->id]);

        $this->command->info('RBAC seeding completed successfully!');
        $this->command->info('Default admin user: admin@sekolah.com / password');
    }
}
