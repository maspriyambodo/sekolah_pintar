# Sekolah API Documentation

## Base URL
```
/api/v1
```

## Authentication

### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "admin@sekolah.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "refresh_token": "a1b2c3d4e5f6...",
        "token_type": "bearer",
        "expires_in": 1800,
        "user": {
            "id": 1,
            "name": "Administrator",
            "email": "admin@sekolah.com",
            "role": "admin",
            "is_active": true,
            "roles": [...],
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "role": "guru"
}
```

### Refresh Token
```http
POST /api/v1/auth/refresh
Content-Type: application/json

{
    "refresh_token": "a1b2c3d4e5f6..."
}
```

### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {access_token}
```

### Get Current User
```http
GET /api/v1/auth/me
Authorization: Bearer {access_token}
```

---

## Dashboard Endpoints

Requires authentication. Role: admin, guru

### Get Dashboard Overview
```http
GET /api/v1/dashboard
Authorization: Bearer {access_token}
```

### Get Summary Cards
```http
GET /api/v1/dashboard/summary-cards
Authorization: Bearer {access_token}
```

### Get Financial Analytics
```http
GET /api/v1/dashboard/financial-analytics
Authorization: Bearer {access_token}
```

### Get Academic & Attendance Analytics
```http
GET /api/v1/dashboard/academic-attendance
Authorization: Bearer {access_token}
```

### Get Counseling Insights
```http
GET /api/v1/dashboard/counseling-insights
Authorization: Bearer {access_token}
```

---

## Siswa Endpoints

Requires authentication. Role: admin, guru

### List All Siswa
```http
GET /api/v1/siswa
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `kelas_id` - Filter by kelas
- `status` - Filter by status (aktif, lulus, pindah)
- `jenis_kelamin` - Filter by gender (L, P)
- `search` - Search by nama or nis
- `per_page` - Items per page (default: 15)
- `cursor` - Cursor for pagination

**Response:**
```json
{
    "success": true,
    "message": "Siswa retrieved successfully",
    "data": [
        {
            "id": 1,
            "nis": "12345",
            "nama": "Budi Santoso",
            "jenis_kelamin": "L",
            "tanggal_lahir": "2005-01-15",
            "alamat": "Jl. Merdeka No. 1",
            "status": "aktif",
            "kelas": {
                "id": 1,
                "nama_kelas": "X-A",
                "tingkat": 10,
                "tahun_ajaran": "2023/2024"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "next_cursor": "eyJpZCI6MTAwfQ",
        "prev_cursor": null,
        "has_more": true
    }
}
```

### Get Single Siswa
```http
GET /api/v1/siswa/{id}
Authorization: Bearer {access_token}
```

### Create Siswa
```http
POST /api/v1/siswa
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "sys_user_id": 1,
    "nis": "12345",
    "nama": "Budi Santoso",
    "jenis_kelamin": "L",
    "tanggal_lahir": "2005-01-15",
    "alamat": "Jl. Merdeka No. 1",
    "mst_kelas_id": 1,
    "status": "aktif"
}
```

### Update Siswa
```http
PUT /api/v1/siswa/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Budi Santoso Updated",
    "alamat": "Jl. Sudirman No. 2"
}
```

### Delete Siswa
```http
DELETE /api/v1/siswa/{id}
Authorization: Bearer {access_token}
```

### Get Siswa by Kelas
```http
GET /api/v1/siswa/kelas/{kelas_id}
Authorization: Bearer {access_token}
```

### Get Absensi Summary
```http
GET /api/v1/siswa/{id}/absensi-summary?start_date=2024-01-01&end_date=2024-01-31
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "success": true,
    "message": "Absensi summary retrieved successfully",
    "data": {
        "hadir": 20,
        "izin": 2,
        "sakit": 1,
        "alpha": 0,
        "total": 23
    }
}
```

### Naik Kelas
```http
POST /api/v1/siswa/{id}/naik-kelas
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "kelas_id": 2
}
```

### Lulus
```http
POST /api/v1/siswa/{id}/lulus
Authorization: Bearer {access_token}
```

---

## Kelas Endpoints

Requires authentication. Role: admin, guru

### List All Kelas
```http
GET /api/v1/kelas
Authorization: Bearer {access_token}
```

### Create Kelas
```http
POST /api/v1/kelas
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama_kelas": "X-A",
    "tingkat": 10,
    "tahun_ajaran": "2024/2025",
    "wali_kelas_id": 1
}
```

### Get Single Kelas
```http
GET /api/v1/kelas/{id}
Authorization: Bearer {access_token}
```

### Update Kelas
```http
PUT /api/v1/kelas/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama_kelas": "X-B"
}
```

### Delete Kelas
```http
DELETE /api/v1/kelas/{id}
Authorization: Bearer {access_token}
```

### Get Siswa in Kelas
```http
GET /api/v1/kelas/{id}/siswa
Authorization: Bearer {access_token}
```

### Get Kelas by Tingkat
```http
GET /api/v1/kelas/tingkat/{tingkat}
Authorization: Bearer {access_token}
```

---

## Mapel Endpoints

Requires authentication. Role: admin, guru

### List All Mapel
```http
GET /api/v1/mapel
Authorization: Bearer {access_token}
```

### Create Mapel
```http
POST /api/v1/mapel
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Matematika",
    "kode": "MTK",
    "kategori": "Umum"
}
```

### Get Single Mapel
```http
GET /api/v1/mapel/{id}
Authorization: Bearer {access_token}
```

### Update Mapel
```http
PUT /api/v1/mapel/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Matematika Peminatan"
}
```

### Delete Mapel
```http
DELETE /api/v1/mapel/{id}
Authorization: Bearer {access_token}
```

### Get Gurus Teaching Mapel
```http
GET /api/v1/mapel/{id}/gurus
Authorization: Bearer {access_token}
```

---

## Guru Endpoints

Requires authentication. Role: admin, guru

### List All Guru
```http
GET /api/v1/guru
Authorization: Bearer {access_token}
```

### Create Guru
```http
POST /api/v1/guru
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "sys_user_id": 1,
    "nip": "198501012020121001",
    "nama": "Budi Guru",
    "jenis_kelamin": "L",
    "tanggal_lahir": "1985-01-01",
    "alamat": "Jl. Guru No. 1",
    "no_hp": "081234567890"
}
```

### Get Single Guru
```http
GET /api/v1/guru/{id}
Authorization: Bearer {access_token}
```

### Update Guru
```http
PUT /api/v1/guru/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Budi Guru Updated"
}
```

### Delete Guru
```http
DELETE /api/v1/guru/{id}
Authorization: Bearer {access_token}
```

### Get Guru by Mapel
```http
GET /api/v1/guru/mapel/{mapelId}
Authorization: Bearer {access_token}
```

### Get Guru Absensi Summary
```http
GET /api/v1/guru/{id}/absensi-summary
Authorization: Bearer {access_token}
```

---

## Wali Endpoints

Requires authentication. Role: admin, guru

### List All Wali
```http
GET /api/v1/wali
Authorization: Bearer {access_token}
```

### Create Wali
```http
POST /api/v1/wali
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Budi Wali",
    "hubungan": "Ayah",
    "no_hp": "081234567890",
    "alamat": "Jl. Wali No. 1"
}
```

### Get Single Wali
```http
GET /api/v1/wali/{id}
Authorization: Bearer {access_token}
```

### Update Wali
```http
PUT /api/v1/wali/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Budi Wali Updated"
}
```

### Delete Wali
```http
DELETE /api/v1/wali/{id}
Authorization: Bearer {access_token}
```

### Get Siswa by Wali
```http
GET /api/v1/wali/{id}/siswa
Authorization: Bearer {access_token}
```

---

## Absensi Guru Endpoints

Requires authentication.

### List All Absensi Guru
```http
GET /api/v1/absensi-guru
Authorization: Bearer {access_token}
```

### Create Absensi Guru
```http
POST /api/v1/absensi-guru
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "guru_id": 1,
    "tanggal": "2024-01-15",
    "status": "hadir",
    "keterangan": "Tepat waktu"
}
```

### Get Single Absensi Guru
```http
GET /api/v1/absensi-guru/{id}
Authorization: Bearer {access_token}
```

### Update Absensi Guru
```http
PUT /api/v1/absensi-guru/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "status": "izin"
}
```

### Delete Absensi Guru
```http
DELETE /api/v1/absensi-guru/{id}
Authorization: Bearer {access_token}
```

### Get Absensi by Guru
```http
GET /api/v1/absensi-guru/guru/{guruId}
Authorization: Bearer {access_token}
```

### Get Absensi Summary by Guru
```http
GET /api/v1/absensi-guru/guru/{guruId}/summary
Authorization: Bearer {access_token}
```

---

## Absensi Siswa Endpoints

Requires authentication.

### List All Absensi Siswa
```http
GET /api/v1/absensi-siswa
Authorization: Bearer {access_token}
```

### Create Absensi Siswa
```http
POST /api/v1/absensi-siswa
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "tanggal": "2024-01-15",
    "status": "hadir",
    "keterangan": ""
}
```

### Get Single Absensi Siswa
```http
GET /api/v1/absensi-siswa/{id}
Authorization: Bearer {access_token}
```

### Update Absensi Siswa
```http
PUT /api/v1/absensi-siswa/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "status": "sakit"
}
```

### Delete Absensi Siswa
```http
DELETE /api/v1/absensi-siswa/{id}
Authorization: Bearer {access_token}
```

### Get Absensi by Siswa
```http
GET /api/v1/absensi-siswa/siswa/{siswaId}
Authorization: Bearer {access_token}
```

### Get Absensi by Date Range
```http
POST /api/v1/absensi-siswa/date-range
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "start_date": "2024-01-01",
    "end_date": "2024-01-31",
    "kelas_id": 1
}
```

### Get Absensi Summary by Siswa
```http
GET /api/v1/absensi-siswa/siswa/{siswaId}/summary
Authorization: Bearer {access_token}
```

---

## Bimbingan Konseling Endpoints

### BK Jenis Endpoints

Requires authentication. Role: admin, guru

#### List All BK Jenis
```http
GET /api/v1/bk/jenis
Authorization: Bearer {access_token}
```

#### Create BK Jenis
```http
POST /api/v1/bk/jenis
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Kedisiplinan",
    "deskripsi": "Masalah kedisiplinan siswa"
}
```

#### Get Single BK Jenis
```http
GET /api/v1/bk/jenis/{id}
Authorization: Bearer {access_token}
```

#### Update BK Jenis
```http
PUT /api/v1/bk/jenis/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "Kedisiplinan Updated"
}
```

#### Delete BK Jenis
```http
DELETE /api/v1/bk/jenis/{id}
Authorization: Bearer {access_token}
```

### BK Kasus Endpoints

Requires authentication. Role: admin, guru

#### List All BK Kasus
```http
GET /api/v1/bk/kasus
Authorization: Bearer {access_token}
```

#### Create BK Kasus
```http
POST /api/v1/bk/kasus
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "jenis_id": 1,
    "tanggal": "2024-01-15",
    "deskripsi": "Siswa terlambat masuk kelas",
    "tindakan": "Peringatan"
}
```

#### Get Single BK Kasus
```http
GET /api/v1/bk/kasus/{id}
Authorization: Bearer {access_token}
```

#### Update BK Kasus
```http
PUT /api/v1/bk/kasus/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "tindakan": "Peringatan keras"
}
```

#### Delete BK Kasus
```http
DELETE /api/v1/bk/kasus/{id}
Authorization: Bearer {access_token}
```

#### Get Kasus by Siswa
```http
GET /api/v1/bk/kasus/siswa/{siswaId}
Authorization: Bearer {access_token}
```

---

## Perpustakaan Endpoints

### Buku Endpoints

Requires authentication. Role: admin, guru

#### List All Buku
```http
GET /api/v1/perpustakaan/buku
Authorization: Bearer {access_token}
```

#### List Available Buku
```http
GET /api/v1/perpustakaan/buku/available
Authorization: Bearer {access_token}
```

#### Create Buku
```http
POST /api/v1/perpustakaan/buku
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "isbn": "978-979-044-123-4",
    "judul": "Matematika untuk SMA",
    "penulis": "Budi Author",
    "penerbit": "Gramedia",
    "tahun_terbit": 2020,
    "jumlah": 10
}
```

#### Get Single Buku
```http
GET /api/v1/perpustakaan/buku/{id}
Authorization: Bearer {access_token}
```

#### Update Buku
```http
PUT /api/v1/perpustakaan/buku/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "judul": "Matematika untuk SMA Updated"
}
```

#### Delete Buku
```http
DELETE /api/v1/perpustakaan/buku/{id}
Authorization: Bearer {access_token}
```

#### Get Peminjaman by Buku
```http
GET /api/v1/perpustakaan/buku/{id}/peminjaman
Authorization: Bearer {access_token}
```

### Peminjaman Endpoints

Requires authentication. Role: admin, guru

#### List All Peminjaman
```http
GET /api/v1/perpustakaan/peminjaman
Authorization: Bearer {access_token}
```

#### Create Peminjaman
```http
POST /api/v1/perpustakaan/peminjaman
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "buku_id": 1,
    "siswa_id": 1,
    "tanggal_pinjam": "2024-01-15",
    "tanggal_kembali": "2024-01-22"
}
```

#### List Overdue Peminjaman
```http
GET /api/v1/perpustakaan/peminjaman/overdue
Authorization: Bearer {access_token}
```

#### Get Single Peminjaman
```http
GET /api/v1/perpustakaan/peminjaman/{id}
Authorization: Bearer {access_token}
```

#### Update Peminjaman
```http
PUT /api/v1/perpustakaan/peminjaman/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "tanggal_kembali": "2024-01-29"
}
```

#### Delete Peminjaman
```http
DELETE /api/v1/perpustakaan/peminjaman/{id}
Authorization: Bearer {access_token}
```

#### Pengembalian Buku
```http
POST /api/v1/perpustakaan/peminjaman/{id}/pengembalian
Authorization: Bearer {access_token}
```

#### Get Peminjaman by Siswa
```http
GET /api/v1/perpustakaan/peminjaman/siswa/{siswaId}
Authorization: Bearer {access_token}
```

---

## Akademik Endpoints

### Ujian Endpoints

Requires authentication. Role: admin, guru

#### List All Ujian
```http
GET /api/v1/akademik/ujian
Authorization: Bearer {access_token}
```

#### Create Ujian
```http
POST /api/v1/akademik/ujian
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "UTS Matematika",
    "mapel_id": 1,
    "kelas_id": 1,
    "tanggal": "2024-01-15",
    "waktu_menit": 90,
    "tipe": "uts"
}
```

#### Get Single Ujian
```http
GET /api/v1/akademik/ujian/{id}
Authorization: Bearer {access_token}
```

#### Update Ujian
```http
PUT /api/v1/akademik/ujian/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nama": "UTS Matematika Updated"
}
```

#### Delete Ujian
```http
DELETE /api/v1/akademik/ujian/{id}
Authorization: Bearer {access_token}
```

#### Get Nilai by Ujian
```http
GET /api/v1/akademik/ujian/{id}/nilai
Authorization: Bearer {access_token}
```

#### Get Ujian by Kelas
```http
GET /api/v1/akademik/ujian/kelas/{kelasId}
Authorization: Bearer {access_token}
```

### Nilai Endpoints

Requires authentication. Role: admin, guru

#### List All Nilai
```http
GET /api/v1/akademik/nilai
Authorization: Bearer {access_token}
```

#### Create Nilai
```http
POST /api/v1/akademik/nilai
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "ujian_id": 1,
    "nilai": 85,
    "deskripsi": "Siswa mengerjakan dengan baik"
}
```

#### Get Single Nilai
```http
GET /api/v1/akademik/nilai/{id}
Authorization: Bearer {access_token}
```

#### Update Nilai
```http
PUT /api/v1/akademik/nilai/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "nilai": 90
}
```

#### Delete Nilai
```http
DELETE /api/v1/akademik/nilai/{id}
Authorization: Bearer {access_token}
```

#### Get Nilai by Siswa
```http
GET /api/v1/akademik/nilai/siswa/{siswaId}
Authorization: Bearer {access_token}
```

#### Get Nilai by Ujian
```http
GET /api/v1/akademik/nilai/ujian/{ujianId}
Authorization: Bearer {access_token}
```

#### Get Rata-rata Nilai by Siswa
```http
GET /api/v1/akademik/nilai/siswa/{siswaId}/rata-rata
Authorization: Bearer {access_token}
```

### Ranking Endpoints

Requires authentication. Role: admin, guru

#### List All Ranking
```http
GET /api/v1/akademik/ranking
Authorization: Bearer {access_token}
```

#### Create Ranking
```http
POST /api/v1/akademik/ranking
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "kelas_id": 1,
    "periode": "2024/2025 Ganjil",
    "ranking": 1,
    "rata-rata": 95.5
}
```

#### Generate Ranking
```http
POST /api/v1/akademik/ranking/generate
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "kelas_id": 1,
    "periode": "2024/2025 Ganjil"
}
```

#### Get Single Ranking
```http
GET /api/v1/akademik/ranking/{id}
Authorization: Bearer {access_token}
```

#### Update Ranking
```http
PUT /api/v1/akademik/ranking/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "ranking": 2
}
```

#### Delete Ranking
```http
DELETE /api/v1/akademik/ranking/{id}
Authorization: Bearer {access_token}
```

#### Get Ranking by Kelas
```http
GET /api/v1/akademik/ranking/kelas/{kelasId}
Authorization: Bearer {access_token}
```

### Rapor Endpoints

Requires authentication. Role: admin, guru

#### List All Rapor
```http
GET /api/v1/akademik/rapor
Authorization: Bearer {access_token}
```

#### Create Rapor
```http
POST /api/v1/akademik/rapor
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "kelas_id": 1,
    "periode": "2024/2025 Ganjil",
    "tanggal_cetak": "2024-01-20"
}
```

#### Get Single Rapor
```http
GET /api/v1/akademik/rapor/{id}
Authorization: Bearer {access_token}
```

#### Update Rapor
```http
PUT /api/v1/akademik/rapor/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "catatan": "Siswa berprestasi"
}
```

#### Delete Rapor
```http
DELETE /api/v1/akademik/rapor/{id}
Authorization: Bearer {access_token}
```

#### Get Rapor by Siswa
```http
GET /api/v1/akademik/rapor/siswa/{siswaId}
Authorization: Bearer {access_token}
```

#### Get Rapor Detail
```http
GET /api/v1/akademik/rapor/{id}/detail
Authorization: Bearer {access_token}
```

---

## Admin Endpoints

Requires authentication. Role: admin only

### User Management

#### List All Users
```http
GET /api/v1/admin/users
Authorization: Bearer {access_token}
```

#### Create User
```http
POST /api/v1/admin/users
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "Password123!",
    "is_active": true
}
```

#### Get Single User
```http
GET /api/v1/admin/users/{id}
Authorization: Bearer {access_token}
```

#### Update User
```http
PUT /api/v1/admin/users/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "name": "Updated User"
}
```

#### Delete User
```http
DELETE /api/v1/admin/users/{id}
Authorization: Bearer {access_token}
```

#### Toggle User Active Status
```http
POST /api/v1/admin/users/{id}/toggle-active
Authorization: Bearer {access_token}
```

#### Assign Roles to User
```http
POST /api/v1/admin/users/{id}/assign-roles
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "roles": ["admin", "guru"]
}
```

### Role Management

#### List All Roles
```http
GET /api/v1/admin/roles
Authorization: Bearer {access_token}
```

#### Create Role
```http
POST /api/v1/admin/roles
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "name": "new_role",
    "display_name": "New Role",
    "description": "Description of new role"
}
```

#### Get Single Role
```http
GET /api/v1/admin/roles/{id}
Authorization: Bearer {access_token}
```

#### Update Role
```http
PUT /api/v1/admin/roles/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "display_name": "Updated Role"
}
```

#### Delete Role
```http
DELETE /api/v1/admin/roles/{id}
Authorization: Bearer {access_token}
```

#### Get Role Permissions
```http
GET /api/v1/admin/roles/{id}/permissions
Authorization: Bearer {access_token}
```

#### Assign Permissions to Role
```http
POST /api/v1/admin/roles/{id}/assign-permissions
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "permissions": ["siswa.view", "siswa.create"]
}
```

### Permission Management

#### List All Permissions
```http
GET /api/v1/admin/permissions
Authorization: Bearer {access_token}
```

#### Create Permission
```http
POST /api/v1/admin/permissions
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "name": "new_permission",
    "display_name": "New Permission",
    "description": "Description of new permission"
}
```

#### Get Single Permission
```http
GET /api/v1/admin/permissions/{id}
Authorization: Bearer {access_token}
```

#### Update Permission
```http
PUT /api/v1/admin/permissions/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "display_name": "Updated Permission"
}
```

#### Delete Permission
```http
DELETE /api/v1/admin/permissions/{id}
Authorization: Bearer {access_token}
```

---

## Keuangan Endpoints

Requires authentication. Role: admin, staff

### Tarif SPP Endpoints

#### List All Tarif SPP
```http
GET /api/v1/keuangan/tarif-spp
Authorization: Bearer {access_token}
```

#### Create Tarif SPP
```http
POST /api/v1/keuangan/tarif-spp
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "kelas_id": 1,
    "bulan": "Januari",
    "tahun": 2024,
    "jumlah": 500000
}
```

#### Get Tarif SPP by Kelas
```http
GET /api/v1/keuangan/tarif-spp/kelas/{kelasId}
Authorization: Bearer {access_token}
```

#### Get Single Tarif SPP
```http
GET /api/v1/keuangan/tarif-spp/{id}
Authorization: Bearer {access_token}
```

#### Update Tarif SPP
```http
PUT /api/v1/keuangan/tarif-spp/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "jumlah": 550000
}
```

#### Delete Tarif SPP
```http
DELETE /api/v1/keuangan/tarif-spp/{id}
Authorization: Bearer {access_token}
```

### Pembayaran SPP Endpoints

#### List All Pembayaran SPP
```http
GET /api/v1/keuangan/pembayaran-spp
Authorization: Bearer {access_token}
```

#### Create Pembayaran SPP
```http
POST /api/v1/keuangan/pembayaran-spp
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "tarif_spp_id": 1,
    "jumlah_bayar": 500000,
    "tanggal_bayar": "2024-01-15",
    "metode_pembayaran": "transfer"
}
```

#### Bayar SPP
```http
POST /api/v1/keuangan/pembayaran-spp/bayar
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "siswa_id": 1,
    "bulan": "Januari",
    "tahun": 2024,
    "jumlah_bayar": 500000,
    "metode_pembayaran": "transfer"
}
```

#### Get Pembayaran SPP by Siswa
```http
GET /api/v1/keuangan/pembayaran-spp/siswa/{siswaId}
Authorization: Bearer {access_token}
```

#### Get Status Pembayaran SPP Siswa
```http
GET /api/v1/keuangan/pembayaran-spp/siswa/{siswaId}/status
Authorization: Bearer {access_token}
```

#### Get Single Pembayaran SPP
```http
GET /api/v1/keuangan/pembayaran-spp/{id}
Authorization: Bearer {access_token}
```

#### Update Pembayaran SPP
```http
PUT /api/v1/keuangan/pembayaran-spp/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "jumlah_bayar": 550000
}
```

#### Delete Pembayaran SPP
```http
DELETE /api/v1/keuangan/pembayaran-spp/{id}
Authorization: Bearer {access_token}
```

---

## File Upload Endpoints

Requires authentication.

### Upload File
```http
POST /api/v1/files/upload
Authorization: Bearer {access_token}
Content-Type: multipart/form-data

file: <binary>
folder: "profile-photos" (optional)
```

**Response:**
```json
{
    "success": true,
    "message": "File uploaded successfully",
    "data": {
        "file_name": "a1b2c3d4.jpg",
        "original_name": "photo.jpg",
        "file_path": "2024/01/user-1/profile-photos/a1b2c3d4.jpg",
        "file_size": 12345,
        "mime_type": "image/jpeg",
        "extension": "jpg",
        "url": "https://minio.example.com/sekolah-files/2024/01/user-1/profile-photos/a1b2c3d4.jpg"
    }
}
```

### Get Presigned URL
```http
POST /api/v1/files/presigned-url
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "file_path": "2024/01/user-1/profile-photos/a1b2c3d4.jpg",
    "expiration": 15
}
```

### Delete File
```http
DELETE /api/v1/files/delete
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "file_path": "2024/01/user-1/profile-photos/a1b2c3d4.jpg"
}
```

---

## Health Check
```http
GET /api/health
```

**Response:**
```json
{
    "success": true,
    "message": "API is running",
    "timestamp": "2024-01-01T12:00:00.000000Z",
    "version": "1.0.0"
}
```

---

## Error Responses

### 400 Bad Request
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["Email sudah terdaftar"]
    }
}
```

### 401 Unauthorized
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### 403 Forbidden
```json
{
    "success": false,
    "message": "Forbidden - Insufficient permissions"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Siswa not found"
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Internal server error"
}
```

---

## Rate Limiting

- General API: 60 requests per minute per user/IP
- Auth endpoints: 5 requests per minute per IP

---

## Response Format

All responses follow this standardized format:

```json
{
    "success": true|false,
    "message": "Human readable message",
    "data": { ... },
    "meta": { ... } // For paginated responses
}
```

---

## Roles and Permissions

| Role | Access |
|------|--------|
| admin | Full access to all endpoints |
| guru | Access to master data, akademik, BK, absensi, perpustakaan |
| staff | Access to keuangan endpoints |
| siswa | Limited access (view own data) |
| wali | Access to view student's data |

---

## Authentication Flow

1. **Login**: POST `/api/v1/auth/login` with email and password
2. **Receive**: Access token (30 min expiry), refresh token
3. **Use Token**: Include `Authorization: Bearer {access_token}` in headers
4. **Refresh**: POST `/api/v1/auth/refresh` when token expires
5. **Logout**: POST `/api/v1/auth/logout` to invalidate tokens
