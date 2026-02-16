<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysMenu;
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

            // Mapel permissions
            ['code' => 'mapel.view', 'name' => 'View Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.create', 'name' => 'Create Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.update', 'name' => 'Update Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.delete', 'name' => 'Delete Mata Pelajaran', 'module' => 'mapel'],

            // Pembayaran/SPP permissions
            ['code' => 'pembayaran.view', 'name' => 'View Pembayaran', 'module' => 'pembayaran'],
            ['code' => 'pembayaran.create', 'name' => 'Create Pembayaran', 'module' => 'pembayaran'],
            ['code' => 'pembayaran.update', 'name' => 'Update Pembayaran', 'module' => 'pembayaran'],
            ['code' => 'pembayaran.delete', 'name' => 'Delete Pembayaran', 'module' => 'pembayaran'],

            // Ujian permissions
            ['code' => 'ujian.view', 'name' => 'View Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.create', 'name' => 'Create Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.update', 'name' => 'Update Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.delete', 'name' => 'Delete Ujian', 'module' => 'ujian'],

            // Rapor permissions
            ['code' => 'rapor.view', 'name' => 'View Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.create', 'name' => 'Create Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.update', 'name' => 'Update Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.delete', 'name' => 'Delete Rapor', 'module' => 'rapor'],

            // Peminjaman permissions
            ['code' => 'peminjaman.view', 'name' => 'View Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.create', 'name' => 'Create Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.update', 'name' => 'Update Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.delete', 'name' => 'Delete Peminjaman', 'module' => 'peminjaman'],

            // Menu management permissions
            ['code' => 'menus.view', 'name' => 'View Menus', 'module' => 'menus'],
            ['code' => 'menus.create', 'name' => 'Create Menus', 'module' => 'menus'],
            ['code' => 'menus.update', 'name' => 'Update Menus', 'module' => 'menus'],
            ['code' => 'menus.delete', 'name' => 'Delete Menus', 'module' => 'menus'],
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

        // Create menus
        $menus = [
            // Dashboard
            [
                'nama_menu' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'bi-grid-1x2',
                'urutan' => 1,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'bi-grid-1x2', 'urutan' => 1, 'is_active' => true],
                ],
            ],
            // Data Master
            [
                'nama_menu' => 'Data Master',
                'url' => '#',
                'icon' => 'bi-database',
                'urutan' => 2,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Siswa', 'url' => '/siswa', 'icon' => 'bi-people', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Guru', 'url' => '/guru', 'icon' => 'bi-person-badge', 'urutan' => 2, 'is_active' => true],
                    ['nama_menu' => 'Kelas', 'url' => '/kelas', 'icon' => 'bi-door-open', 'urutan' => 3, 'is_active' => true],
                    ['nama_menu' => 'Mata Pelajaran', 'url' => '/mapel', 'icon' => 'bi-book', 'urutan' => 4, 'is_active' => true],
                ],
            ],
            // Akademik
            [
                'nama_menu' => 'Akademik',
                'url' => '#',
                'icon' => 'bi-book',
                'urutan' => 3,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Nilai', 'url' => '/nilai', 'icon' => 'bi-clipboard-data', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Absensi', 'url' => '/absensi', 'icon' => 'bi-calendar-check', 'urutan' => 2, 'is_active' => true],
                    ['nama_menu' => 'Ujian', 'url' => '/ujian', 'icon' => 'bi-file-earmark-text', 'urutan' => 3, 'is_active' => true],
                    ['nama_menu' => 'Rapor', 'url' => '/rapor', 'icon' => 'bi-file-earmark-ruled', 'urutan' => 4, 'is_active' => true],
                ],
            ],
            // Bimbingan Konseling
            [
                'nama_menu' => 'Bimbingan Konseling',
                'url' => '/bk',
                'icon' => 'bi-heart-pulse',
                'urutan' => 4,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'BK', 'url' => '/bk', 'icon' => 'bi-heart-pulse', 'urutan' => 1, 'is_active' => true],
                ],
            ],
            // Perpustakaan
            [
                'nama_menu' => 'Perpustakaan',
                'url' => '/perpustakaan',
                'icon' => 'bi-bookshelf',
                'urutan' => 5,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Perpustakaan', 'url' => '/perpustakaan', 'icon' => 'bi-bookshelf', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Peminjaman', 'url' => '/peminjaman', 'icon' => 'bi-arrow-left-right', 'urutan' => 2, 'is_active' => true],
                ],
            ],
            // Pembayaran
            [
                'nama_menu' => 'Pembayaran',
                'url' => '/pembayaran',
                'icon' => 'bi-credit-card',
                'urutan' => 6,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Pembayaran SPP', 'url' => '/pembayaran', 'icon' => 'bi-credit-card', 'urutan' => 1, 'is_active' => true],
                ],
            ],
            // Settings
            [
                'nama_menu' => 'Pengaturan',
                'url' => '#',
                'icon' => 'bi-gear',
                'urutan' => 7,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Users', 'url' => '/users', 'icon' => 'bi-person', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Roles', 'url' => '/roles', 'icon' => 'bi-shield', 'urutan' => 2, 'is_active' => true],
                    ['nama_menu' => 'Menu Management', 'url' => '/menus', 'icon' => 'bi-list', 'urutan' => 3, 'is_active' => true]
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            // Get permission ID based on menu name
            $permissionCode = match ($menuData['nama_menu']) {
                'Dashboard' => 'dashboard.view',
                'Data Master' => 'siswa.view',
                'Akademik' => 'nilai.view',
                'Bimbingan Konseling' => 'bk.view',
                'Perpustakaan' => 'perpustakaan.view',
                'Pembayaran' => 'pembayaran.view',
                'Pengaturan' => 'users.view',
                default => null,
            };
            $permission = $permissionCode ? SysPermission::where('code', $permissionCode)->first() : null;

            $menu = SysMenu::firstOrCreate(
                ['nama_menu' => $menuData['nama_menu'], 'parent_id' => null],
                array_merge($menuData, ['sys_permission_id' => $permission?->id])
            );

            // Create children menus
            foreach ($children as $childData) {
                $childPermissionCode = match ($childData['nama_menu']) {
                    'Siswa' => 'siswa.view',
                    'Guru' => 'guru.view',
                    'Kelas' => 'kelas.view',
                    'Mata Pelajaran' => 'mapel.view',
                    'Nilai' => 'nilai.view',
                    'Absensi' => 'absensi.view',
                    'Ujian' => 'ujian.view',
                    'Rapor' => 'rapor.view',
                    'BK' => 'bk.view',
                    'Perpustakaan' => 'perpustakaan.view',
                    'Peminjaman' => 'peminjaman.view',
                    'Pembayaran SPP' => 'pembayaran.view',
                    'Users' => 'users.view',
                    'Roles' => 'roles.view',
                    'Dashboard' => 'dashboard.view',
                    default => null,
                };
                $childPermission = $childPermissionCode ? SysPermission::where('code', $childPermissionCode)->first() : null;

                SysMenu::firstOrCreate(
                    ['nama_menu' => $childData['nama_menu'], 'parent_id' => $menu->id],
                    array_merge($childData, ['sys_permission_id' => $childPermission?->id])
                );
            }
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
            'siswa', 'guru', 'kelas', 'mapel', 'nilai', 'absensi', 'ujian', 'rapor', 'bk', 'perpustakaan', 'peminjaman', 'pembayaran'
        ])->whereIn('code', [
            'siswa.view', 'siswa.create', 'siswa.update',
            'guru.view',
            'kelas.view',
            'mapel.view', 'mapel.create', 'mapel.update',
            'nilai.view', 'nilai.create', 'nilai.update',
            'absensi.view', 'absensi.create', 'absensi.update',
            'ujian.view', 'ujian.create', 'ujian.update',
            'rapor.view', 'rapor.create', 'rapor.update',
            'bk.view', 'bk.create', 'bk.update',
            'perpustakaan.view', 'perpustakaan.create', 'perpustakaan.update',
            'peminjaman.view', 'peminjaman.create', 'peminjaman.update',
            'pembayaran.view', 'pembayaran.create', 'pembayaran.update',
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
