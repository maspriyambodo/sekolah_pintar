# Sekolah Pintar API - Backend RESTful API

Backend RESTful API untuk sistem informasi sekolah lengkap dengan arsitektur Clean Architecture, JWT Authentication, Redis Caching, dan MinIO File Storage.

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Arsitektur](#arsitektur)
- [Modul Sistem](#modul-sistem)
- [Struktur Database](#struktur-database)
- [Instalasi](#instalasi)
- [API Endpoints](#api-endpoints)
- [Dokumentasi](#dokumentasi)

---

## ✨ Fitur Utama

### Clean Architecture
Arsitektur berlapis dengan pemisahan tanggung jawab yang jelas:
- **Controller** - Menangani HTTP request/response
- **Service** - Business logic layer
- **Repository** - Data access layer
- **Model** - Data entities

### JWT Authentication
- php-open-source-saver/jwt-auth untuk token-based auth
- Redis blacklist untuk token invalidation
- Refresh token mechanism
- Role-based access control

### RBAC (Role-Based Access Control)
Sistem izin akses berbasis peran dengan 4 role utama:
| Role | Deskripsi |
|------|-----------|
| Admin | Akses penuh ke semua fitur sistem |
| Guru | Akses ke fitur akademik dan absensi |
| Siswa | Akses ke data pribadi (nilai, absensi, rapor) |
| Wali | Akses ke data anak (nilai, absensi, pembayaran) |

### Redis Optimization
- **Cache** - Caching query dan response
- **Queue** - Job processing (email, notifikasi)
- **Session** - Session management
- **Rate Limiting** - API rate limiting

### MinIO/S3 Integration
- File upload dengan presigned URL
- Support untuk gambar, dokumen, dan file multimedia
- Organized bucket structure

### Performance Optimization
- Eager loading untuk menghindari N+1 query
- Cursor pagination untuk data besar
- Database indexing untuk query optimization
- Query caching

---

## 🛠 Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 11 + PHP 8.2 |
| Database | MySQL 8.0+ / MariaDB 10.6+ |
| Cache/Queue | Redis 6.0+ |
| Storage | MinIO / S3 |
| Container | Docker & Docker Compose |
| Web Server | Nginx |

---

## 🏗 Arsitektur

```
┌─────────────────────────────────────────────────────────────┐
│                        API Layer                            │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Controllers │  │  Middleware │  │  Request Validation │  │
│  └──────┬──────┘  └─────────────┘  └─────────────────────┘  │
└─────────┼───────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────┐
│                     Service Layer                           │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ Business Logic: Auth, Siswa, Guru, Kelas, Mapel, dll  │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────┬───────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────┐
│                    Repository Layer                         │
│  ┌───────────────────────────────────────────────────────┐  │
│  │ Data Access: Eloquent ORM + Repository Pattern        │  │
│  └───────────────────────────────────────────────────────┘  │
└─────────┬───────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────────────┐
│                      Model Layer                            │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────────────┐   │
│  │  Master  │  │ System   │  │      Transaction         │   │
│  │  Data    │  │ Tables   │  │      Tables              │   │
│  └──────────┘  └──────────┘  └──────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 📦 Modul Sistem

### 1. Manajemen Sistem

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Autentikasi** | Login, Logout, Reset Password | `sys_users`, `sessions`, `password_reset_tokens` |
| **Manajemen Role** | Definisi dan pengelolaan role pengguna | `sys_roles`, `sys_user_roles` |
| **Manajemen Permission** | Hak akses dan izin pengguna | `sys_permissions`, `sys_role_permissions` |
| **Menu Navigasi** | Hierarki menu sistem (parent-child) | `sys_menus` |
| **Data Referensi** | Data referensi sistem (jenis kelamin, status, dll) | `sys_references` |
| **Logging & Audit** | Aktivitas, login, dan error logging | `sys_activity_logs`, `sys_login_logs`, `sys_error_logs` |
| **Profil Sekolah** | Data dan pengaturan sekolah (multi-tenant) | `mst_sekolah`, `sys_sekolah_settings` |

### 2. Master Data

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Data Guru** | Profil guru, NIP, NUPTK | `mst_guru`, `mst_guru_mapel` |
| **Data Siswa** | Profil siswa, NIS, kelas | `mst_siswa` |
| **Data Wali Murid** | Profil wali dan relasi dengan siswa | `mst_wali`, `mst_wali_murid`, `mst_siswa_wali` |
| **Data Kelas** | Kelas, tingkat, tahun ajaran, wali kelas | `mst_kelas` |
| **Data Mata Pelajaran** | Kode dan nama mata pelajaran | `mst_mapel` |

### 3. Akademik

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Materi Pembelajaran** | Upload materi, video pembelajaran | `mst_materi`, `trx_log_akses_materi` |
| **Tugas** | Penugasan per guru-mapel dan kelas | `mst_tugas`, `trx_tugas_siswa` |
| **Bank Soal** | Kumpulan soal (PG/Essay) dan opsi jawaban | `mst_soal`, `mst_soal_opsi` |
| **Ujian Online** | Jadwal ujian dan sesi siswa | `trx_ujian`, `trx_ujian_user`, `trx_ujian_jawaban` |
| **Penilaian** | Nilai, rapor, dan ranking | `trx_nilai`, `trx_rapor`, `trx_rapor_detail`, `trx_ranking` |
| **Presensi & Absensi** | Absensi siswa dan guru harian | `trx_absensi_siswa`, `trx_absensi_guru`, `trx_presensi` |
| **Forum Diskusi** | Topik dan balasan diskusi | `trx_forum` |

### 4. Keuangan (SPP)

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Tarif SPP** | Konfigurasi tarif per kelas dan tahun | `mst_tarif_spp` |
| **Pembayaran** | Pembayaran SPP per bulan | `trx_pembayaran_spp` |

### 5. Bimbingan Konseling (BK)

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Master BK** | Kategori dan jenis kasus BK | `mst_bk_kategori`, `mst_bk_jenis` |
| **Kasus BK** | Pelaporan kasus siswa | `trx_bk_kasus` |
| **Sesi Konseling** | Jadwal dan metode konseling | `trx_bk_sesi` |
| **Tindakan** | Tindakan penanganan | `trx_bk_tindakan` |
| **Hasil & Rekomendasi** | Hasil penanganan dan rekomendasi | `trx_bk_hasil` |
| **Lampiran** | File bukti dan dokumentasi | `trx_bk_lampiran` |
| **Keterlibatan Wali** | Pelibatan wali dalam penanganan | `trx_bk_wali` |

### 6. Perpustakaan

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Data Buku** | Katalog buku (ISBN, stok) | `mst_buku` |
| **Peminjaman** | Peminjaman dan pengembalian buku | `trx_peminjaman_buku` |

### 7. PPDB (Penerimaan Peserta Didik Baru)

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Gelombang Pendaftaran** | Periode dan biaya pendaftaran | `ppdb_gelombang` |
| **Data Pendaftaran** | Data pendaftar dan status | `ppdb_pendaftar` |
| **Dokumen Persyaratan** | Upload dan verifikasi dokumen | `ppdb_dokumen` |

### 8. SPK (Sistem Pendukung Keputusan)

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Kriteria** | Kriteria penilaian dengan bobot | `spk_kriteria` |
| **Penilaian** | Penilaian siswa per kriteria | `spk_penilaian` |
| **Hasil Perhitungan** | Skor dan peringkat siswa | `spk_hasil` |

### 9. Ekstrakurikuler

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Data Ekstrakurikuler** | Master data ekstrakurikuler dan pembina | `mst_ekstrakurikuler` |
| **Pendaftaran Siswa** | Pendaftaran dan manajemen siswa ekstrakurikuler | `trx_ekstrakurikuler_siswa` |

### 9. Organisasi

| Modul | Deskripsi | Tabel |
|-------|-----------|-------|
| **Data Organisasi** | Master data Organisasi dan pembina | `mst_organisasi` |
| **Data Organisasi Jabatan** | Master Data Jabatan Organisasi | `mst_organisasi_jabatan` |
 **Data Anggota Organisasi** | Data Anggota Organisasi | `trx_organisasi_anggota` |

-------

## 📊 Struktur Database

### Master Tables
| Table | Deskripsi |
|-------|-----------|
| `mst_kelas` | Data kelas |
| `mst_mapel` | Data mata pelajaran |
| `mst_guru` | Data guru |
| `mst_guru_mapel` | Relasi guru-mapel |
| `mst_siswa` | Data siswa |
| `mst_wali` | Data wali murid |
| `mst_wali_murid` | Relasi wali-murid |
| `mst_tarif_spp` | Konfigurasi tarif SPP |
| `mst_buku` | Data buku perpustakaan |
| `mst_bk_jenis` | Jenis kasus BK |
| `mst_bk_kategori` | Kategori BK |
| `mst_soal` | Bank soal ujian |
| `mst_soal_opsi` | Opsi jawaban soal |
| `mst_sekolah` | Data sekolah (multi-tenant) |

### Transaction Tables
| Table | Deskripsi |
|-------|-----------|
| `trx_ujian` | Data ujian |
| `trx_ujian_user` | Partisipasi siswa dalam ujian |
| `trx_ujian_jawaban` | Jawaban siswa |
| `trx_nilai` | Nilai siswa |
| `trx_rapor` | Data rapor |
| `trx_rapor_detail` | Detail nilai rapor |
| `trx_ranking` | Peringkat siswa |
| `trx_absensi_guru` | Absensi guru |
| `trx_absensi_siswa` | Absensi siswa |
| `trx_pembayaran_spp` | Pembayaran SPP |
| `trx_peminjaman_buku` | Peminjaman buku |
| `trx_bk_kasus` | Kasus BK |
| `trx_bk_hasil` | Hasil penanganan BK |
| `trx_bk_sesi` | Sesi konseling |
| `trx_bk_tindakan` | Tindakan BK |
| `trx_bk_lampiran` | Lampiran kasus BK |
| `trx_bk_wali` | Keterlibatan wali dalam BK |
| `trx_ekstrakurikuler_siswa` | Pendaftaran siswa ekstrakurikuler |

### System Tables
| Table | Deskripsi |
|-------|-----------|
| `sys_users` | User accounts |
| `sys_roles` | Role definitions |
| `sys_permissions` | Permission definitions |
| `sys_user_roles` | User-role mapping |
| `sys_role_permissions` | Role-permission mapping |
| `sys_activity_logs` | Activity logging |
| `sys_error_logs` | Error logging |
| `sys_login_logs` | Login history |
| `sys_menus` | Menu definitions |
| `sys_references` | Reference data |
| `sys_sekolah_settings` | Pengaturan sekolah (key-value) |

---

## 🚀 Instalasi

### Menggunakan Docker (Recommended)

```bash
# Clone repository
git clone <repository-url> sekolah
cd sekolah

# Start containers
docker-compose up -d

# Install dependencies di container
docker-compose exec app composer install

# Setup environment
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan jwt:secret

# Setup database
docker-compose exec app php artisan migrate --seed
```

Akses aplikasi di: `http://localhost:8080`

### Instalasi Manual

```bash
cd /Users/bodo/www/sekolah/src

# Install Dependencies
composer install

# Environment Setup
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Database Setup
mysql -u root -p db_sekolah < database/db_sekolah.sql
php artisan db:seed --class=RbacSeeder

# Cache Configuration (Production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## � API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Login |
| POST | `/api/v1/auth/register` | Register |
| POST | `/api/v1/auth/refresh` | Refresh token |
| POST | `/api/v1/auth/logout` | Logout (auth required) |
| GET | `/api/v1/auth/me` | Get current user (auth required) |

### Siswa
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/siswa` | List all siswa |
| POST | `/api/v1/siswa` | Create siswa |
| GET | `/api/v1/siswa/{id}` | Get single siswa |
| PUT | `/api/v1/siswa/{id}` | Update siswa |
| DELETE | `/api/v1/siswa/{id}` | Delete siswa |
| GET | `/api/v1/siswa/kelas/{kelas_id}` | Get siswa by kelas |
| GET | `/api/v1/siswa/{id}/absensi-summary` | Get absensi summary |
| POST | `/api/v1/siswa/{id}/naik-kelas` | Naik kelas |
| POST | `/api/v1/siswa/{id}/lulus` | Lulus |

### Guru
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/guru` | List all guru |
| POST | `/api/v1/guru` | Create guru |
| GET | `/api/v1/guru/{id}` | Get single guru |
| PUT | `/api/v1/guru/{id}` | Update guru |
| DELETE | `/api/v1/guru/{id}` | Delete guru |

### Sekolah
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/sekolah` | List all sekolah |
| POST | `/api/v1/sekolah` | Create sekolah |
| GET | `/api/v1/sekolah/{id}` | Get single sekolah |
| PUT | `/api/v1/sekolah/{id}` | Update sekolah |
| DELETE | `/api/v1/sekolah/{id}` | Delete sekolah |
| GET | `/api/v1/sekolah/uuid/{uuid}` | Get sekolah by UUID |
| GET | `/api/v1/sekolah/{id}/settings` | List settings |
| POST | `/api/v1/sekolah/{id}/settings` | Create setting |
| GET | `/api/v1/sekolah/{id}/settings/{id}` | Get setting |
| PUT | `/api/v1/sekolah/{id}/settings/{id}` | Update setting |
| DELETE | `/api/v1/sekolah/{id}/settings/{id}` | Delete setting |
| GET | `/api/v1/sekolah/{id}/settings-key/{key}` | Get setting by key |

### Kelas
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/kelas` | List all kelas |
| POST | `/api/v1/kelas` | Create kelas |
| GET | `/api/v1/kelas/{id}` | Get single kelas |
| PUT | `/api/v1/kelas/{id}` | Update kelas |
| DELETE | `/api/v1/kelas/{id}` | Delete kelas |

### Mapel
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/mapel` | List all mapel |
| POST | `/api/v1/mapel` | Create mapel |
| GET | `/api/v1/mapel/{id}` | Get single mapel |
| PUT | `/api/v1/mapel/{id}` | Update mapel |
| DELETE | `/api/v1/mapel/{id}` | Delete mapel |

### Absensi
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/absensi/siswa` | List absensi siswa |
| POST | `/api/v1/absensi/siswa` | Create absensi siswa |
| GET | `/api/v1/absensi/guru` | List absensi guru |
| POST | `/api/v1/absensi/guru` | Create absensi guru |

### Nilai & Ujian
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/nilai` | List nilai |
| POST | `/api/v1/nilai` | Create nilai |
| GET | `/api/v1/ujian` | List ujian |
| POST | `/api/v1/ujian` | Create ujian |
| GET | `/api/v1/rapor` | List rapor |
| POST | `/api/v1/rapor` | Create rapor |

### Pembayaran
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/pembayaran` | List pembayaran |
| POST | `/api/v1/pembayaran` | Create pembayaran |
| GET | `/api/v1/pembayaran/siswa/{id}` | Get pembayaran by siswa |

### File Upload
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/files/upload` | Upload file |
| POST | `/api/v1/files/presigned-url` | Get presigned URL |
| DELETE | `/api/v1/files/delete` | Delete file |

### Health Check
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/health` | Health check |

Lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md) untuk dokumentasi lengkap.

---

## � Dokumentasi

| Dokumen | Deskripsi |
|---------|-----------|
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Dokumentasi lengkap endpoint API |
| [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) | Penjelasan struktur project |
| [OPTIMIZATION_RECOMMENDATIONS.md](OPTIMIZATION_RECOMMENDATIONS.md) | Rekomendasi optimasi |
| [AGENTS.md](AGENTS.md) | AI Agents configuration |

---

## 🔧 Development

### Commands
```bash
# Start development server
php artisan serve

# Run queue worker
php artisan queue:work

# Run tests
php artisan test

# Code formatting
./vendor/bin/pint

# Clear caches
php artisan optimize:clear
```

### HTTP Testing
Gunakan file di folder `http/` untuk testing dengan REST Client:
- `http/auth.http` - Authentication endpoints
- `http/siswa.http` - Siswa endpoints
- `http/dashboard.http` - Dashboard endpoints

---

## 📈 Performance Targets

| Operation | Target Response Time |
|-----------|---------------------|
| Cached GET endpoint | < 100ms |
| Normal GET endpoint | < 200ms |
| POST/PUT/DELETE | < 300ms |
| Complex queries | < 500ms |
| File upload | < 2s |

---

## 📋 Deployment Checklist

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed RBAC data: `php artisan db:seed --class=RbacSeeder`
- [ ] Cache configurations: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Set proper file permissions (`chmod -R 775 storage bootstrap/cache`)
- [ ] Configure queue workers (Supervisor)
- [ ] Set up Redis for cache/sessions
- [ ] Configure MinIO/S3 credentials
- [ ] Enable HTTPS
- [ ] Set up monitoring & logging

---

## 🔐 Default Credentials

```
Email: admin@sekolah.com
Password: password
```

---

## 📄 License

[MIT License](LICENSE)

---

## 👥 Author

Development Team