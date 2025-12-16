# Sistem Absensi QR Code - Laravel 11

Sistem manajemen absensi berbasis QR Code menggunakan Laravel 11 dengan fitur role-based access control, pembuatan QR code, dan laporan absensi lengkap.

## Fitur Utama

✅ **Authentication & Role-Based Access**
- Laravel Breeze authentication
- 3 roles: Admin, Manager, Employee
- Middleware `CheckRole` untuk proteksi route

✅ **QR Code Management**
- Generate QR code untuk scan absensi
- QR code support expiration
- Tracking scan history
- Download QR code as PNG

✅ **Attendance Tracking**
- Check-in/Check-out via QR scan
- Support multiple status: present, absent, late, sick, leave
- Manual attendance entry (Manager/Admin)
- Unique daily attendance per user

✅ **Reporting**
- Monthly attendance summary
- Per-user attendance detail
- Activity logs tracking
- Export attendance to CSV
- Statistics dashboard

✅ **Data Logging**
- Activity log untuk semua aksi penting
- Track user actions, IP, dan user agent

## Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze (Blade)
- **QR Code**: Endroid/QR-Code
- **Frontend**: Blade Templates + Tailwind CSS

## Instalasi

### 1. Clone Repository
```bash
git clone <repository>
cd Absensi-karyawan
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dengan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absensi_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Jalankan Migrations
```bash
php artisan migrate
```

### 5. Seed Database dengan Data Awal
```bash
php artisan db:seed
```

Data yang di-seed:
- **Roles**: Admin, Manager, Employee
- **Admin User**: admin@absensi.local / password123
- **Manager User**: manager@absensi.local / password123
- **5 Employee Users**: employee1-5@absensi.local / password123

### 6. Build Assets
```bash
npm run build
```

Untuk development dengan hot reload:
```bash
npm run dev
```

### 7. Start Development Server
```bash
php artisan serve
```

Akses di: `http://localhost:8000`

## Struktur Folder

```
Absensi-karyawan/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # QrCodeController, AttendanceController, ReportController
│   │   ├── Middleware/           # CheckRole middleware
│   │   └── Policies/             # QrCodePolicy, AttendancePolicy
│   ├── Models/                   # User, Role, QrCode, Attendance, ActivityLog
│   └── Providers/
├── database/
│   ├── migrations/               # Semua table migrations
│   ├── seeders/                  # RoleSeeder, UserSeeder
│   └── factories/
├── resources/
│   └── views/
│       ├── qr/                   # QR code views
│       ├── attendance/           # Attendance views
│       ├── reports/              # Report & activity log views
│       └── layouts/
├── routes/
│   ├── web.php                   # Main routes dengan middleware role
│   └── auth.php                  # Auth routes
└── public/                       # CSS, JS, images

```

## Database Schema

### Tabel: roles
```php
- id (Primary Key)
- name (string, unique)
- description (string, nullable)
- timestamps
```

### Tabel: users (modified)
```php
- id (Primary Key)
- name, email, password
- role_id (Foreign Key → roles)
- nip (string, unique, nullable)
- phone (string, nullable)
- department (string, nullable)
- timestamps
```

### Tabel: qr_codes
```php
- id (Primary Key)
- user_id (Foreign Key → users)
- code (string, unique)
- qr_data (text) - Base64 QR image
- generated_at (datetime)
- expires_at (datetime, nullable)
- is_active (boolean)
- timestamps
```

### Tabel: attendances
```php
- id (Primary Key)
- user_id (Foreign Key → users)
- qr_code_id (Foreign Key → qr_codes)
- attendance_date (date)
- check_in (time, nullable)
- check_out (time, nullable)
- status (string) - present|absent|late|sick|leave
- notes (text, nullable)
- timestamps
- unique: user_id + attendance_date
```

### Tabel: activity_logs
```php
- id (Primary Key)
- user_id (Foreign Key → users)
- action (string)
- model_type (string, nullable)
- model_id (unsigned bigint, nullable)
- description (text, nullable)
- ip_address (string, nullable)
- user_agent (string, nullable)
- timestamps
```

## Eloquent Relations

### User Model
```php
- hasMany: qrCodes (QrCode)
- hasMany: attendances (Attendance)
- hasMany: activityLogs (ActivityLog)
- belongsTo: role (Role)
```

### Role Model
```php
- hasMany: users (User)
```

### QrCode Model
```php
- belongsTo: user (User)
- hasMany: attendances (Attendance)
```

### Attendance Model
```php
- belongsTo: user (User)
- belongsTo: qrCode (QrCode)
```

### ActivityLog Model
```php
- belongsTo: user (User)
```

## Route & Authorization

### Public Routes
- `/` - Welcome page
- `/login` - Login page
- `/register` - Register page

### Protected Routes (Authenticated)
- `/dashboard` - Dashboard
- `/profile/*` - Profile management

### QR Code Routes (Manager & Admin)
```
GET    /qr-codes                      # List QR codes
POST   /qr-codes/generate             # Generate new QR code
GET    /qr-codes/{id}                 # View QR code detail
GET    /qr-codes/{id}/download        # Download QR code image
POST   /qr-codes/{id}/deactivate      # Deactivate QR code
```

### Attendance Routes (All Authenticated)
```
GET    /attendance                    # List attendance
POST   /attendance/scan               # Scan QR code for check-in/out
GET    /attendance/today-status       # Get today's status (JSON)
```

### Attendance Routes (Manager & Admin)
```
POST   /attendance                    # Manually add attendance
DELETE /attendance/{id}               # Delete attendance record
```

### Report Routes (Manager & Admin)
```
GET    /reports                       # Attendance summary & report
GET    /reports/export                # Export attendance to CSV
GET    /reports/user/{id}             # View user detail report
GET    /reports/activity-log          # View activity logs
```

## Middleware & Authorization

### CheckRole Middleware
Proteksi route berdasarkan role:
```php
Route::middleware('role:manager,admin')->group(function () {
    // Only manager dan admin dapat akses
});
```

### Policies
- **QrCodePolicy**: Authorization untuk QR code operations
- **AttendancePolicy**: Authorization untuk attendance operations

## Models & Methods

### User Model
```php
// Check role methods
hasRole($roleName)          // boolean
isAdmin()                   // boolean
isManager()                 // boolean
isEmployee()                // boolean

// Relations
role()                      // BelongsTo
qrCodes()                   // HasMany
attendances()               // HasMany
activityLogs()              // HasMany
```

### QrCode Model
```php
// Methods
isExpired()                 // boolean
isValid()                   // boolean

// Relations
user()                      // BelongsTo
attendances()               // HasMany
```

### Attendance Model
```php
// Methods
isLate()                    // boolean
getWorkDurationAttribute()  // string

// Relations
user()                      // BelongsTo
qrCode()                    // BelongsTo
```

### ActivityLog Model
```php
// Static methods
log($userId, $action, $description, $modelType, $modelId)

// Relations
user()                      // BelongsTo
```

## Contoh Usage

### Generate QR Code
```php
$qrCode = QrCode::create([
    'user_id' => auth()->id(),
    'code' => 'QR_' . auth()->id() . '_' . uniqid(),
    'qr_data' => $generatedQRImage,
    'generated_at' => now(),
    'expires_at' => now()->addDays(7),
    'is_active' => true
]);
```

### Scan Attendance
```php
$attendance = Attendance::create([
    'user_id' => auth()->id(),
    'qr_code_id' => $qrCode->id,
    'attendance_date' => today(),
    'check_in' => now()->format('H:i:s'),
    'status' => 'present'
]);
```

### Log Activity
```php
ActivityLog::log(
    auth()->id(),
    'qr_scan_checkin',
    'Checked in with QR code',
    'Attendance',
    $attendance->id
);
```

### Generate Report
```php
$attendances = Attendance::whereYear('attendance_date', 2025)
    ->whereMonth('attendance_date', 12)
    ->with('user')
    ->get();
```

## Default Credentials

| Email | Password | Role |
|-------|----------|------|
| z | password123 | Admin |
| manager@absensi.local | password123 | Manager |
| employee1@absensi.local | password123 | Employee |
| employee2@absensi.local | password123 | Employee |
| employee3@absensi.local | password123 | Employee |
| employee4@absensi.local | password123 | Employee |
| employee5@absensi.local | password123 | Employee |

## Security Considerations

✓ Password hashing dengan `bcrypt`
✓ CSRF protection di semua form
✓ Role-based authorization dengan Policies
✓ Activity logging untuk audit trail
✓ IP address & user agent tracking
✓ Soft deletes ready (untuk future enhancement)

## Troubleshooting

### QR Code tidak ter-generate
- Pastikan `endroid/qr-code` sudah terinstall: `composer require endroid/qr-code`
- Check PHP memory_limit jika gambar besar

### Migration error
- Jalankan: `php artisan migrate:refresh`
- Check database connection di `.env`

### Middleware not found
- Pastikan middleware sudah di-register di `bootstrap/app.php`
- Clear cache: `php artisan cache:clear`

### Permission denied
- Check authorization policies
- Pastikan user punya role yang sesuai

## Future Enhancements

- [ ] Mobile app untuk scan QR
- [ ] Biometric attendance support
- [ ] Multi-location tracking
- [ ] Overtime calculation
- [ ] Attendance analytics dashboard
- [ ] Email notifications
- [ ] SMS gateway integration
- [ ] Two-factor authentication

## Support

Untuk pertanyaan atau issue, silakan buat issue di repository.

## License

MIT
