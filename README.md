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

### 1. Modul Master Data

Modul ini mengelola data referensi utama dalam sistem sekolah.

#### 1.1 Data Siswa (`SiswaService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Siswa | Create, Read, Update, Delete data siswa |
| Kelas Management | Penempatan siswa ke kelas |
| Wali Murid | Relasi siswa dengan wali |
| Status Siswa | Aktif, Naik Kelas, Lulus, Keluar |
| Riwayat Kelas | Tracking perpindahan kelas |

#### 1.2 Data Guru (`GuruService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Guru | Create, Read, Update, Delete data guru |
| Mapel Diajar | Relasi guru dengan mata pelajaran |
| Wali Kelas | Penugasan sebagai wali kelas |

#### 1.3 Data Kelas (`KelasService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Kelas | Create, Read, Update, Delete kelas |
| Wali Kelas | Penugasan wali kelas |
| Kapasitas | Monitoring kapasitas kelas |

#### 1.4 Mata Pelajaran (`MapelService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Mapel | Create, Read, Update, Delete mata pelajaran |
| Guru Mapel | Relasi mapel dengan guru pengajar |

#### 1.5 Perpustakaan (`BukuService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Buku | Create, Read, Update, Delete buku |
| Kategori | Pengelompokan buku |
| Peminjaman | Transaksi peminjaman buku |

#### 1.6 Tarif SPP (`TarifSppService`)
| Fitur | Deskripsi |
|-------|-----------|
| Konfigurasi Tarif | Setup tarif SPP per tingkat/kelas |
| Periode | Validitas tarif per tahun ajaran |

#### 1.7 Sekolah (`SekolahService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Sekolah | Create, Read, Update, Delete data sekolah |
| UUID Management | Generate dan management UUID unik per sekolah |
| Status Aktif | Activation/deactivation sekolah |
| Subscription Plan | Management paket langganan (free, premium) |
| Logo Management | Upload dan management logo sekolah |

#### 1.8 Pengaturan Sekolah (`SysSekolahSettings`)
| Fitur | Deskripsi |
|-------|-----------|
| Key-Value Settings | Penyimpanan pengaturan dinamis |
| Tahun Ajaran Aktif | Konfigurasi tahun ajaran berjalan |
| Format Rapor | Konfigurasi format rapor |
| Custom Settings | Pengaturan kustom per sekolah |

---

### 2. Modul Akademik

Modul ini mengelola seluruh aktivitas akademik sekolah.

#### 2.1 Ujian (`UjianService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Ujian | Create, Read, Update, Delete ujian |
| Bank Soal | Manajemen soal ujian |
| Opsi Jawaban | Multiple choice dengan opsi |
| Ujian Siswa | Tracking ujian per siswa |
| Penilaian | Auto-grading dan scoring |

#### 2.2 Nilai (`NilaiService`)
| Fitur | Deskripsi |
|-------|-----------|
| Input Nilai | Entry nilai per siswa per mapel |
| Jenis Nilai | UH, UTS, UAS, Tugas, dll |
| Rata-rata | Kalkulasi nilai rata-rata |
| Konversi | Konversi nilai ke skala 1-100 |

#### 2.3 Rapor (`RaporService`)
| Fitur | Deskripsi |
|-------|-----------|
| Generate Rapor | Pembuatan rapor otomatis |
| Detail Rapor | Nilai per mata pelajaran |
| Catatan Guru | Input catatan untuk rapor |
| Prestasi | Pencatatan prestasi siswa |
| Absensi Rapor | Rekap absensi untuk rapor |

#### 2.4 Ranking (`RankingService`)
| Fitur | Deskripsi |
|-------|-----------|
| Peringkat Kelas | Ranking berdasarkan nilai |
| Peringkat Umum | Ranking sekolah-wide |
| Rekapitulasi | Summary ranking per periode |

---

### 3. Modul Absensi

#### 3.1 Absensi Guru (`AbsensiGuruService`)
| Fitur | Deskripsi |
|-------|-----------|
| Input Absensi | Recording kehadiran guru |
| Rekap Bulanan | Summary absensi per bulan |
| Status | Hadir, Izin, Sakit, Alpha |

#### 3.2 Absensi Siswa (`AbsensiSiswaService`)
| Fitur | Deskripsi |
|-------|-----------|
| Input Absensi | Recording kehadiran siswa per kelas |
| Rekap Harian | Summary absensi harian |
| Rekap Bulanan | Summary absensi per bulan |
| Status | Hadir, Izin, Sakit, Alpha, Bolos |
| Summary | Dashboard ringkasan absensi |

---

### 4. Modul Keuangan

#### 4.1 Pembayaran SPP (`PembayaranSppService`)
| Fitur | Deskripsi |
|-------|-----------|
| Input Pembayaran | Recording pembayaran SPP |
| Riwayat | History pembayaran per siswa |
| Status Lunas | Tracking kelunasan |
| Laporan | Rekap keuangan SPP |
| Tunggakan | Monitoring tunggakan pembayaran |

---

### 5. Modul Perpustakaan

#### 5.1 Peminjaman Buku (`PeminjamanBukuService`)
| Fitur | Deskripsi |
|-------|-----------|
| Peminjaman | Recording pinjam buku |
| Pengembalian | Recording kembali buku |
| Denda | Kalkulasi denda keterlambatan |
| Status | Tersedia, Dipinjam, Hilang |
| Riwayat | History peminjaman per siswa |

---

### 6. Modul Bimbingan Konseling

#### 6.1 BK (`BkJenisService`, `BkKasusService`)
| Fitur | Deskripsi |
|-------|-----------|
| Jenis Kasus | Kategori masalah siswa |
| Kasus | Recording kasus siswa |
| Sesi BK | Jadwal dan catatan sesi konseling |
| Hasil | Outcome dari penanganan kasus |
| Tindakan | Tindakan yang diambil |
| Lampiran | File pendukung (dokumen, foto) |
| Involving Wali | Keterlibatan wali dalam penanganan |

---

### 7. Modul Autentikasi & Otorisasi

#### 7.1 Auth (`AuthService`)
| Fitur | Deskripsi |
|-------|-----------|
| Login | Autentikasi user |
| Register | Pendaftaran user baru |
| Logout | Invalidasi token |
| Refresh Token | Perpanjangan akses token |
| Me | Get current user info |

#### 7.2 User (`UserService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD User | Create, Read, Update, Delete user |
| Profile | Management profil user |
| Password | Reset dan change password |

#### 7.3 Role & Permission (`RoleService`, `PermissionService`)
| Fitur | Deskripsi |
|-------|-----------|
| CRUD Role | Create, Read, Update, Delete role |
| CRUD Permission | Create, Read, Update, Delete permission |
| User Role | Assignment role ke user |
| Role Permission | Assignment permission ke role |

---

### 8. Modul Sistem

#### 8.1 File Upload (`FileUploadService`)
| Fitur | Deskripsi |
|-------|-----------|
| Upload File | Upload file ke storage |
| Presigned URL | Generate URL untuk direct upload |
| Delete File | Hapus file dari storage |

#### 8.2 Logging
| Fitur | Deskripsi |
|-------|-----------|
| Activity Log | Logging aktivitas user |
| Error Log | Recording error sistem |
| Login Log | Tracking login attempts |

---

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

## 🔗 API Endpoints

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

## 📚 Dokumentasi

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