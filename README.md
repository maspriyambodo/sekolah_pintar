# Sekolah API - Backend RESTful API

Backend RESTful API untuk sistem informasi sekolah dengan arsitektur Clean Architecture, JWT Authentication, Redis Caching, dan MinIO File Storage.

## Fitur Utama

- ✅ **Clean Architecture** - Controller → Service → Repository → Model
- ✅ **JWT Authentication** - php-open-source-saver/jwt-auth dengan Redis blacklist
- ✅ **RBAC (Role-Based Access Control)** - Admin, Guru, Siswa, Wali
- ✅ **Redis Optimization** - Cache, Queue, Session, Rate Limiting
- ✅ **MinIO/S3 Integration** - File upload dengan presigned URL
- ✅ **Performance Optimization** - Eager loading, cursor pagination, indexing
- ✅ **API Versioning** - /api/v1
- ✅ **Standardized Response** - Consistent JSON response format

## Teknologi

- **Backend**: Laravel 11 + PHP 8.2
- **Database**: MySQL 8.0+ / MariaDB 10.6+
- **Cache/Queue**: Redis 6.0+
- **Storage**: MinIO / S3
- **Container**: Docker & Docker Compose

## Persyaratan Sistem

- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Redis 6.0+
- Composer 2.0+
- Docker & Docker Compose (opsional)
- MinIO (opsional, untuk file storage)

## Instalasi

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

## Konfigurasi Environment

### Database (.env)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_sekolah
DB_USERNAME=root
DB_PASSWORD=
```

### JWT (.env)
```env
JWT_SECRET=your-secret-key
JWT_TTL=30                    # Access token: 30 menit
JWT_REFRESH_TTL=10080         # Refresh token: 7 hari
JWT_BLACKLIST_ENABLED=true
```

### Redis (.env)
```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### MinIO/S3 (.env)
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_BUCKET=sekolah-files
AWS_ENDPOINT=http://localhost:9000
AWS_USE_PATH_STYLE_ENDPOINT=true
```

## Default Credentials

```
Email: admin@sekolah.com
Password: password
```

## API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Login |
| POST | `/api/v1/auth/register` | Register |
| POST | `/api/v1/auth/refresh` | Refresh token |
| POST | `/api/v1/auth/logout` | Logout (auth required) |
| GET | `/api/v1/auth/me` | Get current user (auth required) |

### Siswa (Admin & Guru only)
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

### File Upload (Auth required)
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

## Struktur Folder

```
sekolah/
├── app/
│   ├── Console/Commands/       # Custom CLI commands
│   ├── Http/
│   │   ├── Controllers/Api/    # API Controllers
│   │   ├── Middleware/         # Custom middleware
│   │   ├── Requests/Api/       # Form request validation
│   │   └── Resources/Api/      # API resources
│   ├── Models/
│   │   ├── Master/             # Master data (siswa, guru, kelas, mapel, etc)
│   │   ├── System/             # System models (users, roles, permissions, logs)
│   │   └── Transaction/        # Transaction models (nilai, absensi, pembayaran, etc)
│   ├── Providers/              # Service providers
│   ├── Repositories/            # Repository pattern
│   │   ├── Contracts/          # Interfaces
│   │   └── Eloquent/           # Implementations
│   ├── Services/               # Business logic layer
│   └── Traits/                 # Reusable traits
├── bootstrap/                  # Laravel bootstrap files
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── db_sekolah.sql          # Database schema dump
├── docker/                     # Docker configuration
│   ├── nginx/                  # Nginx config
│   └── php/                    # PHP config
├── http/                       # HTTP request files (API testing)
├── resources/
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript
│   └── views/                  # Blade templates
├── routes/                     # Route definitions
├── storage/                    # Storage (logs, cache, uploads)
└── tests/                      # Unit & Feature tests
```

Lihat [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) untuk detail lengkap.

## Modul Sistem

### Master Data
- **Siswa** - Data siswa dan wali
- **Guru** - Data guru dan mapel yang diajar
- **Kelas** - Data kelas dan wali kelas
- **Mapel** - Data mata pelajaran
- **Buku** - Data perpustakaan
- **Tarif SPP** - Konfigurasi pembayaran SPP

### Transaksi
- **Absensi** - Absensi siswa dan guru
- **Nilai** - Penilaian dan ujian
- **Rapor** - Raport siswa
- **Pembayaran SPP** - Pembayaran sekolah
- **Peminjaman Buku** - Perpustakaan
- **BK (Bimbingan Konseling)** - Kasus dan penanganan

### Sistem
- **RBAC** - Role & Permission management
- **Activity Log** - Log aktivitas pengguna
- **Error Log** - Log error sistem

## Dokumentasi

- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Dokumentasi lengkap endpoint API
- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - Penjelasan struktur project
- [OPTIMIZATION_RECOMMENDATIONS.md](OPTIMIZATION_RECOMMENDATIONS.md) - Rekomendasi optimasi
- [AGENTS.md](AGENTS.md) - AI Agents configuration

## Development

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

## Performance Targets

| Operation | Target Response Time |
|-----------|---------------------|
| Cached GET endpoint | < 100ms |
| Normal GET endpoint | < 200ms |
| POST/PUT/DELETE | < 300ms |
| Complex queries | < 500ms |
| File upload | < 2s |

## Deployment Checklist

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

## License

[MIT License](LICENSE)

## Author

Development Team
