# Project Structure

## Overview

```
app/
├── Exceptions/              # Custom exception handlers
├── Http/
│   ├── Controllers/         # Controllers
│   │   └── Api/
│   │       └── V1/          # API Version 1 controllers
│   │           ├── AuthController.php
│   │           ├── FileUploadController.php
│   │           └── SiswaController.php
│   ├── Middleware/          # Middleware
│   │   └── RoleMiddleware.php
│   ├── Requests/            # Form Requests (Validation)
│   │   └── Api/
│   │       └── V1/
│   │           ├── LoginRequest.php
│   │           ├── RegisterRequest.php
│   │           └── Siswa/
│   │               ├── CreateSiswaRequest.php
│   │               └── UpdateSiswaRequest.php
│   └── Resources/           # API Resources (Response formatting)
│       └── Api/
│           └── V1/
│               ├── SiswaResource.php
│               └── UserResource.php
├── Models/                  # Eloquent Models
│   ├── Master/              # Master data models
│   │   ├── MstBkJenis.php
│   │   ├── MstBkKategori.php
│   │   ├── MstBuku.php
│   │   ├── MstGuru.php
│   │   ├── MstGuruMapel.php
│   │   ├── MstKelas.php
│   │   ├── MstMapel.php
│   │   ├── MstSiswa.php
│   │   ├── MstSiswaWali.php
│   │   ├── MstWali.php
│   │   └── MstWaliMurid.php
│   ├── System/              # System models
│   │   ├── SysActivityLog.php
│   │   ├── SysErrorLog.php
│   │   ├── SysLoginLog.php
│   │   ├── SysPermission.php
│   │   ├── SysRole.php
│   │   ├── SysRolePermission.php
│   │   ├── SysUser.php
│   │   └── SysUserRole.php
│   └── Transaction/         # Transaction models
│       ├── TrxAbsensiGuru.php
│       ├── TrxAbsensiSiswa.php
│       ├── TrxBkHasil.php
│       ├── TrxBkKasus.php
│       ├── TrxBkLampiran.php
│       ├── TrxBkSesi.php
│       ├── TrxBkTindakan.php
│       ├── TrxBkWali.php
│       ├── TrxNilai.php
│       ├── TrxPeminjamanBuku.php
│       ├── TrxRanking.php
│       ├── TrxRapor.php
│       ├── TrxRaporDetail.php
│       └── TrxUjian.php
├── Providers/               # Service Providers
│   ├── AppServiceProvider.php
│   └── RepositoryServiceProvider.php
├── Repositories/            # Repository Pattern
│   ├── Contracts/           # Interfaces
│   │   ├── BaseRepositoryInterface.php
│   │   ├── SiswaRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   └── Eloquent/            # Implementations
│       ├── BaseRepository.php
│       ├── SiswaRepository.php
│       └── UserRepository.php
├── Services/                # Business Logic Layer
│   ├── AuthService.php
│   ├── FileUploadService.php
│   └── SiswaService.php
└── Traits/                  # Reusable traits
    └── ApiResponseTrait.php

bootstrap/
├── app.php                  # Application bootstrap
└── providers.php            # Provider registrations

config/
├── app.php
├── auth.php                 # Auth configuration (JWT)
├── cache.php
├── database.php
├── filesystems.php
├── jwt.php                  # JWT configuration
└── ...

database/
├── db_sekolah.sql           # Existing database schema
├── factories/               # Model factories
├── migrations/              # Laravel migrations
└── seeders/                 # Database seeders
    ├── DatabaseSeeder.php
    └── RbacSeeder.php       # RBAC seeding

routes/
├── api.php                  # API routes
├── console.php
└── web.php

storage/
├── app/
├── framework/
└── logs/

.env.example                 # Environment template
.env                         # Environment variables
API_DOCUMENTATION.md         # API documentation
OPTIMIZATION_RECOMMENDATIONS.md  # Optimization guide
PROJECT_STRUCTURE.md         # This file
README.md                    # Project readme
```

## Architecture Flow

```
┌─────────────┐
│   Request   │
└──────┬──────┘
       │
       ▼
┌─────────────┐     ┌─────────────┐
│   Routes    │────▶│ Middleware  │
└──────┬──────┘     └──────┬──────┘
       │                    │
       ▼                    ▼
┌─────────────┐     ┌─────────────┐
│ Form Request│────▶│ Controller  │
│ (Validation)│     └──────┬──────┘
└─────────────┘            │
                           ▼
                    ┌─────────────┐
                    │   Service   │
                    │  (Business) │
                    └──────┬──────┘
                           │
                           ▼
                    ┌─────────────┐
                    │ Repository  │
                    └──────┬──────┘
                           │
                           ▼
                    ┌─────────────┐
                    │    Model    │
                    │   (Eloquent)│
                    └──────┬──────┘
                           │
                           ▼
                    ┌─────────────┐
                    │  Database   │
                    └─────────────┘
                           │
                           ▼
                    ┌─────────────┐
                    │   Resource  │
                    │ (Transform) │
                    └──────┬──────┘
                           │
                           ▼
                    ┌─────────────┐
                    │   Response  │
                    └─────────────┘
```

## Key Features

### Clean Architecture
- **Controller**: Handles HTTP requests/responses
- **Service**: Contains business logic
- **Repository**: Data access abstraction
- **Model**: Eloquent ORM
- **Request**: Form validation
- **Resource**: Response transformation

### JWT Authentication
- Access token: 30 minutes
- Refresh token: 7 days
- Redis blacklist support
- Multi-device login support

### Caching Strategy
- Redis for cache storage
- Tagged cache for easy invalidation
- Repository-level caching
- Service-level cache clearing

### File Upload
- MinIO/S3 compatible
- Presigned URL support
- Year/month/user folder structure
- File type validation

### Performance Optimization
- Eager loading relationships
- Cursor pagination
- Select specific columns
- Database indexing recommendations

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest

# Run with coverage
php artisan test --coverage
```
