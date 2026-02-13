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

## Persyaratan Sistem

- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Redis 6.0+
- Composer 2.0+
- MinIO (opsional, untuk file storage)

## Instalasi

### 1. Clone Repository

```bash
cd /Users/bodo/www/sekolah/src
git clone <repository-url> .
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Edit `.env` file dan sesuaikan konfigurasi database, Redis, dan MinIO.

### 4. Database Setup

```bash
# Import existing schema
mysql -u root -p db_sekolah < database/db_sekolah.sql

# Run migrations (if needed)
php artisan migrate

# Seed RBAC data
php artisan db:seed --class=RbacSeeder
```

### 5. Cache Configuration (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Penggunaan

### Default Credentials

```
Email: admin@sekolah.com
Password: password
```

### API Endpoints

#### Authentication
- `POST /api/v1/auth/login` - Login
- `POST /api/v1/auth/register` - Register
- `POST /api/v1/auth/refresh` - Refresh token
- `POST /api/v1/auth/logout` - Logout (auth required)
- `GET /api/v1/auth/me` - Get current user (auth required)

#### Siswa (Admin & Guru only)
- `GET /api/v1/siswa` - List all siswa
- `POST /api/v1/siswa` - Create siswa
- `GET /api/v1/siswa/{id}` - Get single siswa
- `PUT /api/v1/siswa/{id}` - Update siswa
- `DELETE /api/v1/siswa/{id}` - Delete siswa
- `GET /api/v1/siswa/kelas/{kelas_id}` - Get siswa by kelas
- `GET /api/v1/siswa/{id}/absensi-summary` - Get absensi summary
- `POST /api/v1/siswa/{id}/naik-kelas` - Naik kelas
- `POST /api/v1/siswa/{id}/lulus` - Lulus

#### File Upload (Auth required)
- `POST /api/v1/files/upload` - Upload file
- `POST /api/v1/files/presigned-url` - Get presigned URL
- `DELETE /api/v1/files/delete` - Delete file

#### Health Check
- `GET /api/health` - Health check

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/Api/V1/    # API Controllers
│   ├── Middleware/            # Custom middleware
│   ├── Requests/Api/V1/       # Form request validation
│   └── Resources/Api/V1/      # API resources
├── Models/
│   ├── Master/                # Master data (siswa, guru, kelas, etc)
│   ├── System/                # System models (users, roles, logs)
│   └── Transaction/           # Transaction models
├── Repositories/              # Repository pattern
│   ├── Contracts/             # Interfaces
│   └── Eloquent/              # Implementations
├── Services/                  # Business logic
└── Traits/                    # Reusable traits
```

Lihat [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) untuk detail lengkap.

## Dokumentasi API

Lihat [API_DOCUMENTATION.md](API_DOCUMENTATION.md) untuk dokumentasi lengkap endpoint API.

## Optimization

Lihat [OPTIMIZATION_RECOMMENDATIONS.md](OPTIMIZATION_RECOMMENDATIONS.md) untuk:
- Database indexing recommendations
- Query optimization guidelines
- Redis optimization
- Performance targets
- Security best practices

## Konfigurasi Penting

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

## Development

### Run Development Server
```bash
php artisan serve
```

### Run Queue Worker
```bash
php artisan queue:work
```

### Run Tests
```bash
php artisan test
```

### Code Formatting
```bash
./vendor/bin/pint
```

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
- [ ] Set proper file permissions
- [ ] Configure queue workers (Supervisor)
- [ ] Set up Redis for cache/sessions
- [ ] Configure MinIO/S3 credentials
- [ ] Enable HTTPS
- [ ] Set up monitoring

## License

[MIT License](LICENSE)

## Author

Development Team
