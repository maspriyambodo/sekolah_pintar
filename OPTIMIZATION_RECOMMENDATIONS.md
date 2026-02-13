# Optimization Recommendations for Sekolah API

## 1. Database Indexing Recommendations

Based on the existing schema, here are the recommended indexes to improve query performance:

```sql
-- mst_siswa indexes
CREATE INDEX idx_siswa_kelas_status ON mst_siswa(mst_kelas_id, status);
CREATE INDEX idx_siswa_nama ON mst_siswa(nama);
CREATE INDEX idx_siswa_jk ON mst_siswa(jenis_kelamin);

-- trx_absensi_siswa indexes
CREATE INDEX idx_absensi_siswa_tanggal ON trx_absensi_siswa(mst_siswa_id, tanggal);
CREATE INDEX idx_absensi_siswa_status ON trx_absensi_siswa(status);

-- trx_nilai indexes
CREATE INDEX idx_nilai_ujian_siswa ON trx_nilai(trx_ujian_id, mst_siswa_id);
CREATE INDEX idx_nilai_siswa ON trx_nilai(mst_siswa_id);

-- trx_rapor indexes
CREATE INDEX idx_rapor_siswa_semester ON trx_rapor(mst_siswa_id, semester, tahun_ajaran);

-- trx_bk_kasus indexes
CREATE INDEX idx_bk_kasus_siswa ON trx_bk_kasus(mst_siswa_id, status);
CREATE INDEX idx_bk_kasus_guru ON trx_bk_kasus(mst_guru_id);

-- trx_peminjaman_buku indexes
CREATE INDEX idx_pinjam_siswa_status ON trx_peminjaman_buku(mst_siswa_id, status);
CREATE INDEX idx_pinjam_buku_status ON trx_peminjaman_buku(mst_buku_id, status);

-- sys_activity_logs indexes
CREATE INDEX idx_logs_user ON sys_activity_logs(sys_user_id, created_at);
CREATE INDEX idx_logs_module ON sys_activity_logs(module, created_at);
```

## 2. Query Optimization Guidelines

### Eager Loading
Always use eager loading to prevent N+1 query problems:

```php
// Good
$siswa = MstSiswa::with(['kelas', 'user'])->get();

// Bad
$siswa = MstSiswa::all();
foreach ($siswa as $s) {
    echo $s->kelas->nama; // N+1 query
}
```

### Select Specific Columns
Use `select()` to reduce data transfer:

```php
$siswa = MstSiswa::select(['id', 'nis', 'nama', 'mst_kelas_id'])
    ->with(['kelas:id,nama_kelas'])
    ->get();
```

### Cursor Pagination
Use cursor pagination for large datasets:

```php
$siswa = MstSiswa::cursorPaginate(15);
// Use: GET /api/v1/siswa?cursor=eyJpZCI6MTAwfQ
```

### Database Transactions
Use transactions for complex operations:

```php
DB::transaction(function () {
    $siswa = MstSiswa::create($data);
    $siswa->wali()->attach($waliIds);
    // ... other operations
});
```

## 3. Redis Optimization

### Cache Configuration
```bash
# .env
CACHE_STORE=redis
REDIS_CLIENT=phpredis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

### Cache Tags Usage
```php
// Cache with tags for easy invalidation
Cache::tags(['siswa', 'kelas-'.$kelasId])->remember(
    "siswa:kelas:{$kelasId}",
    300,
    fn () => $this->siswaRepository->getByKelas($kelasId)
);

// Invalidate all siswa-related cache
Cache::tags(['siswa'])->flush();
```

### Redis CLI Commands for Monitoring
```bash
# Monitor Redis in real-time
redis-cli monitor

# Check cache hits/misses
redis-cli info stats

# List all keys with prefix
redis-cli keys 'laravel-cache:*'

# Clear all cache
redis-cli flushdb
```

## 4. Laravel Optimization Commands

```bash
# Cache configuration (run in production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Clear all caches (for development)
php artisan optimize:clear

# Preload classes for better performance
php artisan optimize
```

## 5. Performance Targets

| Operation | Target Response Time |
|-----------|---------------------|
| Cached GET endpoint | < 100ms |
| Normal GET endpoint | < 200ms |
| POST/PUT/DELETE | < 300ms |
| Complex queries | < 500ms |
| File upload | < 2s |

## 6. Monitoring & Debugging

### Enable Query Logging (Development Only)
```php
// In AppServiceProvider::boot()
DB::listen(function ($query) {
    Log::debug('SQL Query', [
        'sql' => $query->sql,
        'bindings' => $query->bindings,
        'time' => $query->time . 'ms',
    ]);
});
```

### Use Laravel Telescope (Development)
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Performance Profiling
```php
$start = microtime(true);
// ... your code
$duration = (microtime(true) - $start) * 1000;
Log::info("Operation took: {$duration}ms");
```

## 7. MinIO/S3 Optimization

### Presigned URL Configuration
```php
// Short expiration for sensitive files
$url = $fileUploadService->getPresignedUrl($path, 5); // 5 minutes

// Longer expiration for public files
$url = $fileUploadService->getPresignedUrl($path, 60); // 1 hour
```

### File Upload Best Practices
- Validate file types before upload
- Limit file size (max 10MB recommended)
- Use unique filenames (UUID)
- Organize files by year/month/user structure

## 8. Queue Configuration

### Queue Workers
```bash
# Run queue worker with optimal settings
php artisan queue:work --queue=default --sleep=3 --tries=3 --max-time=3600

# For high traffic, use multiple workers
php artisan queue:work --queue=high,default --sleep=3 --tries=3
```

### Queueable Jobs Example
```php
class ProcessAbsensi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Process absensi in background
    }
}

// Dispatch job
ProcessAbsensi::dispatch($data);
```

## 9. Rate Limiting Configuration

```php
// In RouteServiceProvider or bootstrap/app.php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// Custom rate limiter for auth endpoints
RateLimiter::for('auth', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});
```

## 10. Security Best Practices

1. **Always use HTTPS in production**
2. **Enable JWT blacklist via Redis**
3. **Implement proper CORS settings**
4. **Sanitize all user inputs**
5. **Use prepared statements (automatic in Eloquent)**
6. **Regular security audits with `composer audit`**

## 11. Deployment Checklist

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Seed RBAC data: `php artisan db:seed --class=RbacSeeder`
- [ ] Cache configurations: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Set proper file permissions: `chmod -R 755 storage bootstrap/cache`
- [ ] Configure queue workers (Supervisor)
- [ ] Set up Redis for cache/sessions
- [ ] Configure MinIO/S3 credentials
- [ ] Set up monitoring (Laravel Pulse/Telescope)
- [ ] Configure backup schedules
