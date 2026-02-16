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
        // Create permissions - matching API routes
        $permissions = [
            // Dashboard permissions
            ['code' => 'dashboard.view', 'name' => 'View Dashboard', 'module' => 'dashboard'],
            ['code' => 'dashboard.summary', 'name' => 'View Dashboard Summary', 'module' => 'dashboard'],
            ['code' => 'dashboard.financial', 'name' => 'View Financial Analytics', 'module' => 'dashboard'],
            ['code' => 'dashboard.academic', 'name' => 'View Academic Analytics', 'module' => 'dashboard'],

            // File management permissions
            ['code' => 'files.upload', 'name' => 'Upload Files', 'module' => 'files'],
            ['code' => 'files.delete', 'name' => 'Delete Files', 'module' => 'files'],

            // Siswa permissions
            ['code' => 'siswa.view', 'name' => 'View Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.create', 'name' => 'Create Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.update', 'name' => 'Update Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.delete', 'name' => 'Delete Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.naik-kelas', 'name' => 'Naik Kelas Siswa', 'module' => 'siswa'],
            ['code' => 'siswa.lulus', 'name' => 'Mark Siswa Lulus', 'module' => 'siswa'],

            // Guru permissions
            ['code' => 'guru.view', 'name' => 'View Guru', 'module' => 'guru'],
            ['code' => 'guru.create', 'name' => 'Create Guru', 'module' => 'guru'],
            ['code' => 'guru.update', 'name' => 'Update Guru', 'module' => 'guru'],
            ['code' => 'guru.delete', 'name' => 'Delete Guru', 'module' => 'guru'],

            // Wali permissions
            ['code' => 'wali.view', 'name' => 'View Wali Murid', 'module' => 'wali'],
            ['code' => 'wali.create', 'name' => 'Create Wali Murid', 'module' => 'wali'],
            ['code' => 'wali.update', 'name' => 'Update Wali Murid', 'module' => 'wali'],
            ['code' => 'wali.delete', 'name' => 'Delete Wali Murid', 'module' => 'wali'],

            // Kelas permissions
            ['code' => 'kelas.view', 'name' => 'View Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.create', 'name' => 'Create Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.update', 'name' => 'Update Kelas', 'module' => 'kelas'],
            ['code' => 'kelas.delete', 'name' => 'Delete Kelas', 'module' => 'kelas'],

            // Mapel permissions
            ['code' => 'mapel.view', 'name' => 'View Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.create', 'name' => 'Create Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.update', 'name' => 'Update Mata Pelajaran', 'module' => 'mapel'],
            ['code' => 'mapel.delete', 'name' => 'Delete Mata Pelajaran', 'module' => 'mapel'],

            // Absensi Guru permissions
            ['code' => 'absensi-guru.view', 'name' => 'View Absensi Guru', 'module' => 'absensi-guru'],
            ['code' => 'absensi-guru.create', 'name' => 'Create Absensi Guru', 'module' => 'absensi-guru'],
            ['code' => 'absensi-guru.update', 'name' => 'Update Absensi Guru', 'module' => 'absensi-guru'],
            ['code' => 'absensi-guru.delete', 'name' => 'Delete Absensi Guru', 'module' => 'absensi-guru'],

            // Absensi Siswa permissions
            ['code' => 'absensi-siswa.view', 'name' => 'View Absensi Siswa', 'module' => 'absensi-siswa'],
            ['code' => 'absensi-siswa.create', 'name' => 'Create Absensi Siswa', 'module' => 'absensi-siswa'],
            ['code' => 'absensi-siswa.update', 'name' => 'Update Absensi Siswa', 'module' => 'absensi-siswa'],
            ['code' => 'absensi-siswa.delete', 'name' => 'Delete Absensi Siswa', 'module' => 'absensi-siswa'],

            // BK Jenis permissions
            ['code' => 'bk-jenis.view', 'name' => 'View BK Jenis', 'module' => 'bk-jenis'],
            ['code' => 'bk-jenis.create', 'name' => 'Create BK Jenis', 'module' => 'bk-jenis'],
            ['code' => 'bk-jenis.update', 'name' => 'Update BK Jenis', 'module' => 'bk-jenis'],
            ['code' => 'bk-jenis.delete', 'name' => 'Delete BK Jenis', 'module' => 'bk-jenis'],

            // BK Kasus permissions
            ['code' => 'bk-kasus.view', 'name' => 'View BK Kasus', 'module' => 'bk-kasus'],
            ['code' => 'bk-kasus.create', 'name' => 'Create BK Kasus', 'module' => 'bk-kasus'],
            ['code' => 'bk-kasus.update', 'name' => 'Update BK Kasus', 'module' => 'bk-kasus'],
            ['code' => 'bk-kasus.delete', 'name' => 'Delete BK Kasus', 'module' => 'bk-kasus'],

            // Buku permissions
            ['code' => 'buku.view', 'name' => 'View Buku', 'module' => 'buku'],
            ['code' => 'buku.create', 'name' => 'Create Buku', 'module' => 'buku'],
            ['code' => 'buku.update', 'name' => 'Update Buku', 'module' => 'buku'],
            ['code' => 'buku.delete', 'name' => 'Delete Buku', 'module' => 'buku'],

            // Peminjaman permissions
            ['code' => 'peminjaman.view', 'name' => 'View Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.create', 'name' => 'Create Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.update', 'name' => 'Update Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.delete', 'name' => 'Delete Peminjaman', 'module' => 'peminjaman'],
            ['code' => 'peminjaman.pengembalian', 'name' => 'Pengembalian Buku', 'module' => 'peminjaman'],

            // Ujian permissions
            ['code' => 'ujian.view', 'name' => 'View Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.create', 'name' => 'Create Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.update', 'name' => 'Update Ujian', 'module' => 'ujian'],
            ['code' => 'ujian.delete', 'name' => 'Delete Ujian', 'module' => 'ujian'],

            // Ujian User permissions
            ['code' => 'ujian-user.view', 'name' => 'View Ujian User', 'module' => 'ujian-user'],
            ['code' => 'ujian-user.create', 'name' => 'Create Ujian User', 'module' => 'ujian-user'],
            ['code' => 'ujian-user.update', 'name' => 'Update Ujian User', 'module' => 'ujian-user'],
            ['code' => 'ujian-user.delete', 'name' => 'Delete Ujian User', 'module' => 'ujian-user'],
            ['code' => 'ujian-user.mulai', 'name' => 'Mulai Ujian', 'module' => 'ujian-user'],
            ['code' => 'ujian-user.selesaikan', 'name' => 'Selesaikan Ujian', 'module' => 'ujian-user'],

            // Ujian Jawaban permissions
            ['code' => 'ujian-jawaban.view', 'name' => 'View Ujian Jawaban', 'module' => 'ujian-jawaban'],
            ['code' => 'ujian-jawaban.create', 'name' => 'Create Ujian Jawaban', 'module' => 'ujian-jawaban'],
            ['code' => 'ujian-jawaban.update', 'name' => 'Update Ujian Jawaban', 'module' => 'ujian-jawaban'],
            ['code' => 'ujian-jawaban.delete', 'name' => 'Delete Ujian Jawaban', 'module' => 'ujian-jawaban'],

            // Soals permissions
            ['code' => 'soals.view', 'name' => 'View Bank Soal', 'module' => 'soals'],
            ['code' => 'soals.create', 'name' => 'Create Bank Soal', 'module' => 'soals'],
            ['code' => 'soals.update', 'name' => 'Update Bank Soal', 'module' => 'soals'],
            ['code' => 'soals.delete', 'name' => 'Delete Bank Soal', 'module' => 'soals'],

            // Nilai permissions
            ['code' => 'nilai.view', 'name' => 'View Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.create', 'name' => 'Create Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.update', 'name' => 'Update Nilai', 'module' => 'nilai'],
            ['code' => 'nilai.delete', 'name' => 'Delete Nilai', 'module' => 'nilai'],

            // Ranking permissions
            ['code' => 'ranking.view', 'name' => 'View Ranking', 'module' => 'ranking'],
            ['code' => 'ranking.create', 'name' => 'Create Ranking', 'module' => 'ranking'],
            ['code' => 'ranking.update', 'name' => 'Update Ranking', 'module' => 'ranking'],
            ['code' => 'ranking.delete', 'name' => 'Delete Ranking', 'module' => 'ranking'],
            ['code' => 'ranking.generate', 'name' => 'Generate Ranking', 'module' => 'ranking'],

            // Rapor permissions
            ['code' => 'rapor.view', 'name' => 'View Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.create', 'name' => 'Create Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.update', 'name' => 'Update Rapor', 'module' => 'rapor'],
            ['code' => 'rapor.delete', 'name' => 'Delete Rapor', 'module' => 'rapor'],

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

            // Permission management permissions
            ['code' => 'permissions.view', 'name' => 'View Permissions', 'module' => 'permissions'],
            ['code' => 'permissions.create', 'name' => 'Create Permissions', 'module' => 'permissions'],
            ['code' => 'permissions.update', 'name' => 'Update Permissions', 'module' => 'permissions'],
            ['code' => 'permissions.delete', 'name' => 'Delete Permissions', 'module' => 'permissions'],

            // Menu management permissions
            ['code' => 'menus.view', 'name' => 'View Menus', 'module' => 'menus'],
            ['code' => 'menus.create', 'name' => 'Create Menus', 'module' => 'menus'],
            ['code' => 'menus.update', 'name' => 'Update Menus', 'module' => 'menus'],
            ['code' => 'menus.delete', 'name' => 'Delete Menus', 'module' => 'menus'],

            // Tarif SPP permissions
            ['code' => 'tarif-spp.view', 'name' => 'View Tarif SPP', 'module' => 'tarif-spp'],
            ['code' => 'tarif-spp.create', 'name' => 'Create Tarif SPP', 'module' => 'tarif-spp'],
            ['code' => 'tarif-spp.update', 'name' => 'Update Tarif SPP', 'module' => 'tarif-spp'],
            ['code' => 'tarif-spp.delete', 'name' => 'Delete Tarif SPP', 'module' => 'tarif-spp'],

            // Pembayaran SPP permissions
            ['code' => 'pembayaran-spp.view', 'name' => 'View Pembayaran SPP', 'module' => 'pembayaran-spp'],
            ['code' => 'pembayaran-spp.create', 'name' => 'Create Pembayaran SPP', 'module' => 'pembayaran-spp'],
            ['code' => 'pembayaran-spp.update', 'name' => 'Update Pembayaran SPP', 'module' => 'pembayaran-spp'],
            ['code' => 'pembayaran-spp.delete', 'name' => 'Delete Pembayaran SPP', 'module' => 'pembayaran-spp'],
            ['code' => 'pembayaran-spp.bayar', 'name' => 'Bayar SPP', 'module' => 'pembayaran-spp'],
        ];

        foreach ($permissions as $permission) {
            SysPermission::firstOrCreate(
                ['code' => $permission['code']],
                $permission
            );
        }

        // Create roles - matching API routes
        $roles = [
            [
                'code' => 'admin',
                'name' => 'Administrator',
                'description' => 'Full access to all features',
            ],
            [
                'code' => 'guru',
                'name' => 'Guru',
                'description' => 'Teacher role with teaching access',
            ],
            [
                'code' => 'staff',
                'name' => 'Staff',
                'description' => 'Staff role for administrative tasks',
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

        // Create menus - matching API routes
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
                    ['nama_menu' => 'Wali Murid', 'url' => '/wali', 'icon' => 'bi-person-heart', 'urutan' => 3, 'is_active' => true],
                    ['nama_menu' => 'Kelas', 'url' => '/kelas', 'icon' => 'bi-door-open', 'urutan' => 4, 'is_active' => true],
                    ['nama_menu' => 'Mata Pelajaran', 'url' => '/mapel', 'icon' => 'bi-book', 'urutan' => 5, 'is_active' => true],
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
                    ['nama_menu' => 'Nilai', 'url' => '/akademik/nilai', 'icon' => 'bi-clipboard-data', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Absensi', 'url' => '/akademik/absensi', 'icon' => 'bi-calendar-check', 'urutan' => 2, 'is_active' => true],
                    ['nama_menu' => 'Ujian', 'url' => '/akademik/ujian', 'icon' => 'bi-file-earmark-text', 'urutan' => 3, 'is_active' => true],
                    ['nama_menu' => 'Bank Soals', 'url' => '/akademik/soals', 'icon' => 'bi-question-circle', 'urutan' => 4, 'is_active' => true],
                    ['nama_menu' => 'Ranking', 'url' => '/akademik/ranking', 'icon' => 'bi-trophy', 'urutan' => 5, 'is_active' => true],
                    ['nama_menu' => 'Rapor', 'url' => '/akademik/rapor', 'icon' => 'bi-file-earmark-ruled', 'urutan' => 6, 'is_active' => true],
                ],
            ],
            // Absensi
            [
                'nama_menu' => 'Absensi',
                'url' => '#',
                'icon' => 'bi-calendar-check',
                'urutan' => 4,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Absensi Guru', 'url' => '/absensi-guru', 'icon' => 'bi-person-check', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Absensi Siswa', 'url' => '/absensi-siswa', 'icon' => 'bi-people-check', 'urutan' => 2, 'is_active' => true],
                ],
            ],
            // Bimbingan Konseling
            [
                'nama_menu' => 'Bimbingan Konseling',
                'url' => '#',
                'icon' => 'bi-heart-pulse',
                'urutan' => 5,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'BK Jenis', 'url' => '/bk/jenis', 'icon' => 'bi-tag', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'BK Kasus', 'url' => '/bk/kasus', 'icon' => 'bi-exclamation-triangle', 'urutan' => 2, 'is_active' => true],
                ],
            ],
            // Perpustakaan
            [
                'nama_menu' => 'Perpustakaan',
                'url' => '#',
                'icon' => 'bi-bookshelf',
                'urutan' => 6,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Buku', 'url' => '/perpustakaan/buku', 'icon' => 'bi-book', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Peminjaman', 'url' => '/perpustakaan/peminjaman', 'icon' => 'bi-arrow-left-right', 'urutan' => 2, 'is_active' => true],
                ],
            ],
            // Keuangan
            [
                'nama_menu' => 'Keuangan',
                'url' => '#',
                'icon' => 'bi-credit-card',
                'urutan' => 7,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Tarif SPP', 'url' => '/keuangan/tarif-spp', 'icon' => 'bi-cash', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Pembayaran SPP', 'url' => '/keuangan/pembayaran-spp', 'icon' => 'bi-receipt', 'urutan' => 2, 'is_active' => true],
                ],
            ],
            // Settings
            [
                'nama_menu' => 'Pengaturan',
                'url' => '#',
                'icon' => 'bi-gear',
                'urutan' => 8,
                'is_active' => true,
                'children' => [
                    ['nama_menu' => 'Users', 'url' => '/admin/users', 'icon' => 'bi-person', 'urutan' => 1, 'is_active' => true],
                    ['nama_menu' => 'Roles', 'url' => '/admin/roles', 'icon' => 'bi-shield', 'urutan' => 2, 'is_active' => true],
                    ['nama_menu' => 'Permissions', 'url' => '/admin/permissions', 'icon' => 'bi-key', 'urutan' => 3, 'is_active' => true],
                    ['nama_menu' => 'Menu Management', 'url' => '/admin/menus', 'icon' => 'bi-list', 'urutan' => 4, 'is_active' => true]
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
                'Absensi' => 'absensi-siswa.view',
                'Bimbingan Konseling' => 'bk-kasus.view',
                'Perpustakaan' => 'buku.view',
                'Keuangan' => 'tarif-spp.view',
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
                    'Dashboard' => 'dashboard.view',
                    'Siswa' => 'siswa.view',
                    'Guru' => 'guru.view',
                    'Wali Murid' => 'wali.view',
                    'Kelas' => 'kelas.view',
                    'Mata Pelajaran' => 'mapel.view',
                    'Nilai' => 'nilai.view',
                    'Absensi' => 'absensi-siswa.view',
                    'Ujian' => 'ujian.view',
                    'Bank Soals' => 'soals.view',
                    'Ranking' => 'ranking.view',
                    'Rapor' => 'rapor.view',
                    'Absensi Guru' => 'absensi-guru.view',
                    'Absensi Siswa' => 'absensi-siswa.view',
                    'BK Jenis' => 'bk-jenis.view',
                    'BK Kasus' => 'bk-kasus.view',
                    'Buku' => 'buku.view',
                    'Peminjaman' => 'peminjaman.view',
                    'Tarif SPP' => 'tarif-spp.view',
                    'Pembayaran SPP' => 'pembayaran-spp.view',
                    'Users' => 'users.view',
                    'Roles' => 'roles.view',
                    'Permissions' => 'permissions.view',
                    'Menu Management' => 'menus.view',
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
        $staffRole = SysRole::where('code', 'staff')->first();
        $siswaRole = SysRole::where('code', 'siswa')->first();
        $waliRole = SysRole::where('code', 'wali')->first();

        // Admin gets all permissions
        $allPermissions = SysPermission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Guru permissions - teaching and academic access
        $guruPermissions = SysPermission::whereIn('module', [
            'dashboard', 'siswa', 'guru', 'kelas', 'mapel', 
            'absensi-guru', 'absensi-siswa',
            'bk-jenis', 'bk-kasus',
            'buku', 'peminjaman',
            'ujian', 'ujian-user', 'ujian-jawaban', 'soals',
            'nilai', 'ranking', 'rapor'
        ])->whereIn('code', [
            // Dashboard
            'dashboard.view', 'dashboard.summary', 'dashboard.financial', 'dashboard.academic',
            // Data Master
            'siswa.view', 'siswa.create', 'siswa.update', 'siswa.naik-kelas', 'siswa.lulus',
            'guru.view',
            'kelas.view',
            'mapel.view', 'mapel.create', 'mapel.update',
            // Absensi
            'absensi-guru.view', 'absensi-guru.create', 'absensi-guru.update',
            'absensi-siswa.view', 'absensi-siswa.create', 'absensi-siswa.update',
            // BK
            'bk-jenis.view', 'bk-jenis.create', 'bk-jenis.update',
            'bk-kasus.view', 'bk-kasus.create', 'bk-kasus.update',
            // Perpustakaan
            'buku.view', 'buku.create', 'buku.update',
            'peminjaman.view', 'peminjaman.create', 'peminjaman.update', 'peminjaman.pengembalian',
            // Akademik
            'ujian.view', 'ujian.create', 'ujian.update',
            'ujian-user.view', 'ujian-user.create',
            'ujian-jawaban.view', 'ujian-jawaban.create', 'ujian-jawaban.update',
            'soals.view', 'soals.create', 'soals.update',
            'nilai.view', 'nilai.create', 'nilai.update',
            'ranking.view', 'ranking.create', 'ranking.generate',
            'rapor.view', 'rapor.create', 'rapor.update',
        ])->get();
        $guruRole->permissions()->sync($guruPermissions->pluck('id'));

        // Staff permissions - administrative and finance access
        $staffPermissions = SysPermission::whereIn('module', [
            'dashboard', 'siswa', 'kelas', 'tarif-spp', 'pembayaran-spp'
        ])->whereIn('code', [
            'dashboard.view', 'dashboard.summary', 'dashboard.financial',
            'siswa.view',
            'kelas.view',
            'tarif-spp.view', 'tarif-spp.create', 'tarif-spp.update',
            'pembayaran-spp.view', 'pembayaran-spp.create', 'pembayaran-spp.update', 'pembayaran-spp.bayar',
        ])->get();
        $staffRole->permissions()->sync($staffPermissions->pluck('id'));

        // Siswa permissions (view only their own data)
        $siswaPermissions = SysPermission::whereIn('code', [
            'dashboard.view',
            'nilai.view',
            'absensi-siswa.view',
            'rapor.view',
            'ujian-user.view', 'ujian-user.mulai', 'ujian-user.selesaikan',
            'ujian-jawaban.view', 'ujian-jawaban.create', 'ujian-jawaban.update',
            'peminjaman.view', 'peminjaman.create',
        ])->get();
        $siswaRole->permissions()->sync($siswaPermissions->pluck('id'));

        // Wali permissions (view related students)
        $waliPermissions = SysPermission::whereIn('code', [
            'dashboard.view',
            'siswa.view',
            'nilai.view',
            'absensi-siswa.view',
            'rapor.view',
            'bk-kasus.view',
            'pembayaran-spp.view',
        ])->get();
        $waliRole->permissions()->sync($waliPermissions->pluck('id'));

        // Create default admin user
        $adminUser = SysUser::firstOrCreate(
            ['email' => 'admin@sekolah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 1,
                'is_active' => true,
            ]
        );

        // Assign admin role
        $adminUser->roles()->sync([$adminRole->id]);

        $this->command->info('RBAC seeding completed successfully!');
        $this->command->info('Default admin user: admin@sekolah.com / password');
    }
}
