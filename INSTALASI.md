# Langkah-Langkah Instalasi Lengkap

## Prerequisites

- PHP 8.2+ (Laravel 12 requirement)
- Composer
- MySQL 5.7+ atau MariaDB
- Node.js & NPM
- Git

## Step 1: Konfigurasi Environment

Edit file `.env`:

```env
APP_NAME="Absensi Karyawan"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=sync
SESSION_DRIVER=cookie
CACHE_DRIVER=file

MAIL_DRIVER=log
```

## Step 2: Generate Application Key

```bash
php artisan key:generate
```

## Step 3: Jalankan Database Migrations

```bash
php artisan migrate
```

Migrasi akan membuat tabel:
- `roles` - Tabel role (admin, manager, employee)
- `users` - Tabel user dengan tambahan kolom role_id, nip, phone, department
- `qr_codes` - Tabel QR code untuk scan absensi
- `attendances` - Tabel attendance dengan check-in/out tracking
- `activity_logs` - Tabel logging semua aktivitas

## Step 4: Seed Database

```bash
php artisan db:seed
```

Akan membuat:
- 3 Roles: admin, manager, employee
- 1 Admin user: admin@absensi.local
- 1 Manager user: manager@absensi.local
- 5 Employee users: employee1-5@absensi.local
- Semua password: password123

## Step 5: Install Frontend Dependencies

```bash
npm install
```

## Step 6: Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

## Step 7: Jalankan Development Server

```bash
php artisan serve
```

Akses di: http://localhost:8000

## Step 8: Login dengan Credentials Default

Admin User:
- Email: admin@absensi.local
- Password: password123

Manager User:
- Email: manager@absensi.local
- Password: password123

Employee User:
- Email: employee1@absensi.local
- Password: password123

## Verifikasi Instalasi

Cek apakah semua berhasil:

1. ✅ Database migration berhasil
```bash
php artisan tinker
>>> DB::table('roles')->count()
3
>>> DB::table('users')->count()
7
```

2. ✅ Dapat login dengan berbagai role
3. ✅ Admin dapat generate QR code
4. ✅ Employee dapat scan QR code
5. ✅ Manager dapat lihat reports

## Package Dependencies

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "laravel/breeze": "^2.0",
    "endroid/qr-code": "^6.0",
    "symfony/http-foundation": "^7.4"
  }
}
```

## Common Issues & Solutions

### Issue: "SQLSTATE[HY000]: General error: 1030"
**Solution**: Check disk space dan set `innodb_strict_mode = OFF` di MySQL

### Issue: "Class not found" error
**Solution**: Jalankan `composer dump-autoload` dan `php artisan cache:clear`

### Issue: Migration timeout
**Solution**: Increase timeout atau run migration per file:
```bash
php artisan migrate --path=database/migrations/2025_12_01_154304_create_roles_table.php
```

### Issue: QR Code tidak ter-generate
**Solution**: Update dependencies
```bash
composer update endroid/qr-code
```

### Issue: Permission denied pada storage
**Solution**: Fix permissions
```bash
chmod -R 775 storage bootstrap/cache
```

## Next Steps

1. **Customize**: Edit views di `resources/views` sesuai branding
2. **Configure**: Adjust settings di `config` folder
3. **Extend**: Tambah fitur sesuai kebutuhan
4. **Deploy**: Setup untuk production environment

## Production Checklist

- [ ] Set APP_DEBUG=false di .env
- [ ] Generate APP_KEY
- [ ] Setup proper database with backups
- [ ] Configure HTTPS
- [ ] Setup email configuration
- [ ] Configure logging
- [ ] Test all features
- [ ] Setup monitoring
- [ ] Document API endpoints
- [ ] Train users

---

## Dashboard Menu Setelah Login

### Admin Access
- Dashboard
- QR Code Management (Generate, View, Deactivate)
- Attendance (View, Manual Add, Delete)
- Reports (View, Export, User Detail, Activity Logs)
- Profile

### Manager Access
- Dashboard
- QR Code Management
- Attendance (View, Manual Add, Delete)
- Reports (View, Export, User Detail, Activity Logs)
- Profile

### Employee Access
- Dashboard
- Attendance (View, Scan QR)
- Profile

---

Untuk documentation lengkap, lihat `SETUP_GUIDE.md`
