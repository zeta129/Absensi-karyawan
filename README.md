# 🏢 Sistem Absensi QR Code - Laravel 12

[![Laravel](https://img.shields.io/badge/Laravel-12.40-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](https://github.com)

Sistem manajemen absensi berbasis **QR Code** menggunakan **Laravel 12** dengan fitur role-based access control, generate QR code, attendance tracking, dan reporting lengkap.

## 🚀 Quick Start (5 Menit)

```bash
# 1. Install dependencies
composer install && npm install

# 2. Setup environment
cp .env.example .env && php artisan key:generate

# 3. Database setup
php artisan migrate && php artisan db:seed

# 4. Build assets
npm run build

# 5. Run server
php artisan serve
```

**Akses**: `http://localhost:8000`

**Default Credentials**:
- Admin: `admin@absensi.local` / `password123`
- Manager: `manager@absensi.local` / `password123`
- Employee: `employee1@absensi.local` / `password123`

## 📋 Fitur Utama

✅ **Authentication & Role-Based Access**
- Laravel Breeze authentication
- 3 roles: Admin, Manager, Employee
- Role-based middleware protection

✅ **QR Code Management**
- Generate QR codes dengan optional expiration
- Download QR sebagai file PNG
- Deactivate QR codes
- Track QR scan history

✅ **Attendance Tracking**
- Check-in/Check-out via QR scan
- Multiple status support (present, absent, late, sick, leave)
- Manual attendance entry (Manager/Admin)
- Daily unique attendance per user

✅ **Reporting & Analytics**
- Monthly attendance summary
- Per-user attendance detail
- Statistics dashboard
- Export to CSV
- Activity logging

✅ **Security**
- CSRF protection
- Role-based policies
- IP & user agent tracking
- Activity audit trail

## 📊 Database Schema

| Table | Purpose |
|-------|---------|
| `roles` | Role definitions |
| `users` | Users dengan role_id, nip, phone |
| `qr_codes` | QR codes dengan expiration |
| `attendances` | Attendance records |
| `activity_logs` | Audit trail |

## 🎯 Routes

### QR Code (Manager & Admin)
- `GET /qr-codes` - List QR codes
- `POST /qr-codes/generate` - Generate QR
- `GET /qr-codes/{id}` - View detail
- `GET /qr-codes/{id}/download` - Download PNG
- `POST /qr-codes/{id}/deactivate` - Deactivate

### Attendance (All Users)
- `GET /attendance` - List attendance
- `POST /attendance/scan` - Scan QR code
- `GET /attendance/today-status` - Today's status

### Reports (Manager & Admin)
- `GET /reports` - Summary report
- `GET /reports/export` - Export CSV
- `GET /reports/user/{id}` - User detail
- `GET /reports/activity-log` - Activity logs

## 📚 Documentation

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Comprehensive setup guide
- **[QUICKSTART.md](QUICKSTART.md)** - Quick reference
- **[INSTALASI.md](INSTALASI.md)** - Installation in Bahasa Indonesia
- **[TESTING_GUIDE.md](TESTING_GUIDE.md)** - Testing procedures
- **[API_REFERENCE.md](API_REFERENCE.md)** - API documentation
- **[FOLDER_STRUCTURE.md](FOLDER_STRUCTURE.md)** - File reference

## 🛠 Tech Stack

- **Framework**: Laravel 12
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Breeze
- **QR Code**: Endroid/QR-Code
- **Frontend**: Blade + Tailwind CSS
- **Build**: Vite + npm

## 📦 Requirements

- PHP 8.2+
- Composer
- MySQL 5.7+ or MariaDB
- Node.js & npm

## 💻 Usage

```bash
# Development
php artisan serve
npm run dev

# Production
npm run build
php artisan config:cache
php artisan route:cache
```

## 🧪 Testing

See [TESTING_GUIDE.md](TESTING_GUIDE.md) for comprehensive testing procedures.

## 📝 Models & Relations

```
User (1) ──→ (Many) QrCode
User (1) ──→ (Many) Attendance
User (1) ──→ (Many) ActivityLog
User (Many) ──→ (1) Role
QrCode (1) ──→ (Many) Attendance
```

## 🔐 Security

- Password hashing (bcrypt)
- CSRF protection
- Role-based authorization policies
- Activity audit trail
- IP address logging

## 🐛 Troubleshooting

### Port 8000 already in use
```bash
php artisan serve --port=8001
```

### Database error
```bash
php artisan migrate:fresh --seed
```

### Cache issue
```bash
php artisan cache:clear && php artisan config:clear
```

See [SETUP_GUIDE.md](SETUP_GUIDE.md) for more troubleshooting.

## 📄 License

MIT License

## ✅ Status

- ✅ All models & migrations
- ✅ Controllers & business logic
- ✅ Authorization policies
- ✅ Views & templates
- ✅ Routes & middleware
- ✅ Seeders with sample data
- ✅ Comprehensive documentation
- ✅ Testing guide
- ✅ Production ready

---

**Ready to use! 🚀**

Start with [QUICKSTART.md](QUICKSTART.md) for 5-minute setup.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
