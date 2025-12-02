# Quick Start Guide - Sistem Absensi QR Code

## 1. Setup Cepat (5 Menit)

```bash
# Clone & masuk folder
cd c:\laragon\www\Absensi-karyawan

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Run server
php artisan serve
```

Buka browser: **http://localhost:8000**

## 2. Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@absensi.local | password123 |
| Manager | manager@absensi.local | password123 |
| Employee | employee1@absensi.local | password123 |

## 3. Feature Testing

### ✅ Testing Admin Panel

1. Login sebagai **admin@absensi.local**
2. Ke menu **QR Code Management**
3. Click **Generate QR Code**
4. Lihat QR code yang di-generate
5. Download QR code
6. View scanning history

### ✅ Testing Manager

1. Login sebagai **manager@absensi.local**
2. Ke **Attendance** → Lihat attendance semua karyawan
3. Ke **Reports** → Lihat summary dan detail per user
4. Export report ke CSV

### ✅ Testing Employee

1. Login sebagai **employee1@absensi.local**
2. Ke **Attendance**
3. Scan QR code (input QR code)
4. Check-in tercatat
5. Scan lagi untuk check-out

## 4. Struktur Folder Penting

```
Absensi-karyawan/
├── app/
│   ├── Http/Controllers/        # Logic semua fitur
│   ├── Models/                  # Database models
│   ├── Policies/                # Authorization
│   └── Http/Middleware/         # Role middleware
├── database/
│   ├── migrations/              # Table structure
│   └── seeders/                 # Initial data
├── resources/views/             # UI templates
│   ├── qr/                      # QR management
│   ├── attendance/              # Attendance pages
│   ├── reports/                 # Reports pages
│   └── layouts/                 # Master layout
├── routes/
│   └── web.php                  # All routes
└── SETUP_GUIDE.md               # Full documentation
```

## 5. Database Tables

| Table | Purpose |
|-------|---------|
| `roles` | Definisi role (admin, manager, employee) |
| `users` | User data dengan role_id |
| `qr_codes` | QR code yang di-generate |
| `attendances` | Absensi check-in/out |
| `activity_logs` | Audit trail semua aksi |

## 6. API Endpoints (Routes)

### QR Code (Manager/Admin)
```
GET    /qr-codes                  - List QR codes
POST   /qr-codes/generate         - Generate QR
GET    /qr-codes/{id}             - View QR detail
GET    /qr-codes/{id}/download    - Download QR image
POST   /qr-codes/{id}/deactivate  - Deactivate QR
```

### Attendance
```
GET    /attendance                - List attendance
POST   /attendance/scan           - Scan QR code
GET    /attendance/today-status   - Today status
```

### Reports (Manager/Admin)
```
GET    /reports                   - Summary report
GET    /reports/export            - Export CSV
GET    /reports/user/{id}         - User detail
GET    /reports/activity-log      - Activity logs
```

## 7. Customization Tips

### Change App Name
Edit `.env`:
```env
APP_NAME="Nama Sistem Anda"
```

### Change Database
Edit `.env`:
```env
DB_DATABASE=nama_database_anda
DB_USERNAME=username
DB_PASSWORD=password
```

### Add More Users
```bash
php artisan tinker

# Create employee user
User::create([
    'name' => 'John Doe',
    'email' => 'john@company.local',
    'password' => bcrypt('password123'),
    'role_id' => 3, // employee role
    'nip' => 'EMP006'
]);
```

## 8. Development vs Production

### Development Mode
```bash
npm run dev              # Auto-rebuild assets
php artisan serve       # Start dev server
```

### Production Build
```bash
npm run build           # Minify assets
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 9. Database Reset (Testing)

```bash
# Fresh start
php artisan migrate:fresh --seed

# Atau hanya seed ulang
php artisan db:seed
```

## 10. Troubleshooting

### Port 8000 sudah terpakai
```bash
php artisan serve --port=8001
```

### Dependency issue
```bash
composer update
npm install
```

### Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 11. Folder Structure untuk Development

```
resources/views/
├── layouts/
│   └── app.blade.php          # Master layout
├── qr/
│   ├── index.blade.php        # QR list
│   └── show.blade.php         # QR detail
├── attendance/
│   └── index.blade.php        # Attendance list & scanner
├── reports/
│   ├── index.blade.php        # Report summary
│   ├── user-detail.blade.php  # User detail
│   └── activity-log.blade.php # Activity logs
├── auth/                      # Login/register (dari Breeze)
└── welcome.blade.php          # Home page
```

## 12. Key Files untuk Dimodifikasi

- `routes/web.php` - Tambah route baru
- `app/Http/Controllers/*` - Add business logic
- `resources/views/*` - Customize UI
- `app/Models/*` - Add relationships
- `.env` - Configuration

## 13. Testing Checklist

- [ ] Login dengan 3 role berbeda
- [ ] Generate QR code (Admin/Manager)
- [ ] View QR code detail
- [ ] Download QR code
- [ ] Scan QR (Employee)
- [ ] Check-in/check-out working
- [ ] View attendance list
- [ ] Generate report
- [ ] Export CSV
- [ ] Activity log recorded

## 14. Important Commands

```bash
# Generate code
php artisan make:controller ControllerName
php artisan make:model ModelName -m
php artisan make:migration migration_name
php artisan make:middleware MiddlewareName
php artisan make:seeder SeederName
php artisan make:policy PolicyName

# Database
php artisan migrate
php artisan migrate:rollback
php artisan db:seed

# Cache
php artisan cache:clear
php artisan config:cache

# Development
php artisan tinker          # Interactive shell
php artisan serve           # Dev server
```

## 15. File Dependencies

```json
{
  "composer": {
    "laravel/breeze": "2.3.8",
    "endroid/qr-code": "6.0.9",
    "symfony/http-foundation": "7.4"
  },
  "npm": {
    "tailwindcss": "latest",
    "alpinejs": "latest"
  }
}
```

---

**Siap untuk develop! 🚀**

Untuk dokumentasi lengkap, baca `SETUP_GUIDE.md`
