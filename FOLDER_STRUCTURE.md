# 📁 FOLDER STRUCTURE & FILE REFERENCE

## Directory Tree Lengkap

```
Absensi-karyawan/
│
├── app/                                    # Application logic
│   ├── Console/
│   │   ├── Commands/
│   │   └── Kernel.php
│   │
│   ├── Exceptions/
│   │   └── Handler.php
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php              # Base controller
│   │   │   ├── ProfileController.php       # Profile management (Breeze)
│   │   │   ├── QrCodeController.php        ✅ NEW - QR code management
│   │   │   ├── AttendanceController.php    ✅ NEW - Attendance tracking
│   │   │   └── ReportController.php        ✅ NEW - Reports & analytics
│   │   │
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── RedirectIfAuthenticated.php
│   │   │   ├── TrustHosts.php
│   │   │   ├── TrustProxies.php
│   │   │   ├── VerifyCsrfToken.php
│   │   │   ├── ValidatePostSize.php
│   │   │   └── CheckRole.php               ✅ NEW - Role-based middleware
│   │   │
│   │   ├── Requests/
│   │   │   └── (no custom form requests yet)
│   │   │
│   │   └── Policies/
│   │       ├── QrCodePolicy.php            ✅ NEW - Authorization for QR codes
│   │       └── AttendancePolicy.php        ✅ NEW - Authorization for attendance
│   │
│   ├── Models/
│   │   ├── User.php                        ✅ MODIFIED - Added roles & relations
│   │   ├── Role.php                        ✅ NEW - Role model
│   │   ├── QrCode.php                      ✅ NEW - QR code model
│   │   ├── Attendance.php                  ✅ NEW - Attendance model
│   │   └── ActivityLog.php                 ✅ NEW - Activity log model
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php         ✅ MODIFIED - Policies registered
│   │   ├── BroadcastServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   │
│   └── View/
│       └── Components/
│
├── bootstrap/
│   ├── app.php                             ✅ MODIFIED - Middleware alias added
│   └── cache/
│       ├── packages.php
│       └── services.php
│
├── config/                                 # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── broadcasting.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── sanctum.php
│   ├── services.php
│   ├── session.php
│   └── view.php
│
├── database/
│   ├── factories/
│   │   └── UserFactory.php
│   │
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php     (Default - MODIFIED)
│   │   ├── 0001_01_01_000001_create_cache_table.php     (Default)
│   │   ├── 0001_01_01_000002_create_jobs_table.php      (Default)
│   │   ├── 2025_12_01_154304_create_roles_table.php             ✅ NEW
│   │   ├── 2025_12_01_154305_create_qr_codes_table.php          ✅ NEW
│   │   ├── 2025_12_01_154306_create_attendances_table.php       ✅ NEW
│   │   ├── 2025_12_01_154308_create_activity_logs_table.php     ✅ NEW
│   │   └── 2025_12_01_154415_add_role_id_to_users_table.php     ✅ NEW
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php              ✅ MODIFIED - Call seeders
│       ├── RoleSeeder.php                  ✅ NEW - Seed roles
│       └── UserSeeder.php                  ✅ NEW - Seed users
│
├── public/
│   ├── index.php                           # Entry point
│   ├── .htaccess
│   └── robots.txt
│
├── resources/
│   ├── css/
│   │   └── app.css                         # Tailwind CSS
│   │
│   ├── js/
│   │   ├── app.js                          # Main JS
│   │   └── bootstrap.js                    # Bootstrap JS
│   │
│   └── views/
│       ├── auth/                           (from Breeze)
│       │   ├── confirm-password.blade.php
│       │   ├── forgot-password.blade.php
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   ├── reset-password.blade.php
│       │   └── verify-email.blade.php
│       │
│       ├── layouts/
│       │   ├── app.blade.php               (from Breeze)
│       │   └── navigation.blade.php        (from Breeze)
│       │
│       ├── qr/                             ✅ NEW - QR Code views
│       │   ├── index.blade.php             - List & generate QR
│       │   └── show.blade.php              - QR detail & scans
│       │
│       ├── attendance/                     ✅ NEW - Attendance views
│       │   └── index.blade.php             - List & scanner
│       │
│       ├── reports/                        ✅ NEW - Report views
│       │   ├── index.blade.php             - Summary report
│       │   ├── user-detail.blade.php       - User detail
│       │   └── activity-log.blade.php      - Activity logs
│       │
│       ├── dashboard.blade.php             (from Breeze)
│       ├── welcome.blade.php               (customizable)
│       └── profile/                        (from Breeze)
│           ├── delete-user-form.blade.php
│           ├── edit.blade.php
│           ├── update-password-form.blade.php
│           └── update-profile-information-form.blade.php
│
├── routes/
│   ├── api.php                             # API routes (future)
│   ├── channels.php                        # Broadcasting channels
│   ├── console.php                         # Console commands
│   ├── web.php                             ✅ MODIFIED - All web routes
│   └── auth.php                            (from Breeze)
│
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   ├── testing/
│   │   └── views/
│   └── logs/
│       └── laravel.log
│
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   └── TestCase.php
│
├── vendor/                                 # Third-party packages (auto-generated)
│   ├── autoload.php
│   ├── bin/
│   ├── composer/
│   ├── laravel/
│   ├── endroid/                            ✅ QR code library
│   └── ... (other packages)
│
├── .env                                    # Environment config (to be created)
├── .env.example                            # Example env
├── .gitignore
├── .gitattributes
├── artisan                                 # Laravel CLI
├── composer.json                           ✅ MODIFIED - Updated dependencies
├── composer.lock                           ✅ Auto-generated
├── package.json
├── package-lock.json
├── phpunit.xml
├── tailwind.config.js                      # Tailwind configuration
├── vite.config.js                          # Vite build configuration
│
├── SETUP_GUIDE.md                          ✅ NEW - Full documentation
├── INSTALASI.md                            ✅ NEW - Installation guide
├── QUICKSTART.md                           ✅ NEW - Quick reference
├── TESTING_GUIDE.md                        ✅ NEW - Testing procedures
├── API_REFERENCE.md                        ✅ NEW - API documentation
├── RINGKASAN.md                            ✅ NEW - Project summary
└── README.md                               (original - to be updated)
```

---

## File Purpose Reference

### Controllers (app/Http/Controllers/)

#### QrCodeController.php
```php
Purpose: Handle QR code generation and management
Methods:
  - index()          List QR codes
  - generate()       Create new QR code
  - download()       Download QR as PNG
  - deactivate()     Disable QR code
  - show()           View QR details
```

#### AttendanceController.php
```php
Purpose: Handle attendance tracking via QR scan
Methods:
  - index()          List attendance records
  - scan()           Process QR scan
  - todayStatus()    Get today's status
  - store()          Manual add attendance
  - destroy()        Delete attendance
```

#### ReportController.php
```php
Purpose: Generate reports and analytics
Methods:
  - index()          Attendance summary
  - export()         Export to CSV
  - userDetail()     User detail report
  - activityLog()    View activity logs
  - generateSummary() Calculate statistics
```

### Models (app/Models/)

#### User.php
```php
Attributes: id, name, email, password, role_id, nip, phone, department
Relations:
  - role()           belongsTo Role
  - qrCodes()        hasMany QrCode
  - attendances()    hasMany Attendance
  - activityLogs()   hasMany ActivityLog
Methods:
  - hasRole()        Check if has role
  - isAdmin()        Is admin role
  - isManager()      Is manager role
  - isEmployee()     Is employee role
```

#### Role.php
```php
Attributes: id, name, description, timestamps
Relations:
  - users()          hasMany User
```

#### QrCode.php
```php
Attributes: id, user_id, code, qr_data, generated_at, expires_at, 
            is_active, timestamps
Relations:
  - user()           belongsTo User
  - attendances()    hasMany Attendance
Methods:
  - isExpired()      Check if expired
  - isValid()        Check if valid & active
```

#### Attendance.php
```php
Attributes: id, user_id, qr_code_id, attendance_date, check_in, 
            check_out, status, notes, timestamps
Relations:
  - user()           belongsTo User
  - qrCode()         belongsTo QrCode
Methods:
  - isLate()         Check if late
  - getWorkDurationAttribute()  Calculate hours
```

#### ActivityLog.php
```php
Attributes: id, user_id, action, model_type, model_id, description, 
            ip_address, user_agent, timestamps
Relations:
  - user()           belongsTo User
Methods:
  - log()            Static method to create log
```

### Middleware (app/Http/Middleware/)

#### CheckRole.php
```php
Purpose: Verify user has required role
Usage:   Route::middleware('role:admin,manager')
Checks:  User role against allowed roles
Action:  Allow passage or abort with 403
```

### Policies (app/Policies/)

#### QrCodePolicy.php
```php
Methods:
  - viewAny()        Admin/Manager only
  - view()           Owner or admin/manager
  - create()         Manager/Admin only
  - update()         Owner or admin
  - delete()         Admin only
  - restore()        Admin only
  - forceDelete()    Admin only
```

#### AttendancePolicy.php
```php
Methods:
  - viewAny()        Admin/Manager only
  - view()           Owner or admin/manager
  - create()         Manager/Admin only
  - update()         Manager/Admin only
  - delete()         Admin only
  - restore()        Admin only
  - forceDelete()    Admin only
```

### Migrations (database/migrations/)

#### create_roles_table
```php
Columns: id, name (unique), description, timestamps
Purpose: Store role definitions
```

#### create_qr_codes_table
```php
Columns: id, user_id (FK), code (unique), qr_data, 
         generated_at, expires_at, is_active, timestamps
Purpose: Store QR codes for attendance
```

#### create_attendances_table
```php
Columns: id, user_id (FK), qr_code_id (FK), attendance_date,
         check_in, check_out, status, notes, timestamps
Unique:  (user_id, attendance_date)
Purpose: Store attendance records
```

#### create_activity_logs_table
```php
Columns: id, user_id (FK), action, model_type, model_id,
         description, ip_address, user_agent, timestamps
Purpose: Audit trail for system activities
```

#### add_role_id_to_users_table
```php
Added:   role_id (FK), nip (unique), phone, department
Purpose: Extend users table with role and employee info
```

### Seeders (database/seeders/)

#### RoleSeeder.php
```php
Seeds: 3 roles
  - admin
  - manager
  - employee
```

#### UserSeeder.php
```php
Seeds: 7 users
  - 1 admin
  - 1 manager
  - 5 employees
All passwords: password123
```

### Views (resources/views/)

#### qr/index.blade.php
```php
Components:
  - QR code list table
  - Generate modal form
  - Download, view, deactivate buttons
```

#### qr/show.blade.php
```php
Components:
  - QR code display
  - QR details (status, expiry, etc)
  - Scan history table
```

#### attendance/index.blade.php
```php
Components:
  - QR scanner input (employee only)
  - Attendance records table
  - Check in/out status
  - Delete action (admin/manager)
```

#### reports/index.blade.php
```php
Components:
  - Filter form (month, user)
  - Summary statistics cards
  - Attendance records table
  - Export CSV button
```

#### reports/user-detail.blade.php
```php
Components:
  - User information
  - Monthly statistics
  - Attendance detail table
```

#### reports/activity-log.blade.php
```php
Components:
  - Activity logs table
  - User, action, description, IP, timestamp
  - Pagination
```

### Routes (routes/web.php)

```php
Protected Routes (role:manager,admin):
  - GET  /qr-codes
  - POST /qr-codes/generate
  - GET  /qr-codes/{id}
  - GET  /qr-codes/{id}/download
  - POST /qr-codes/{id}/deactivate

Attendance Routes:
  - GET  /attendance
  - POST /attendance/scan
  - GET  /attendance/today-status
  - POST /attendance (manager/admin only)
  - DELETE /attendance/{id} (admin only)

Report Routes (role:manager,admin):
  - GET /reports
  - GET /reports/export
  - GET /reports/user/{user}
  - GET /reports/activity-log
```

### Documentation Files

#### SETUP_GUIDE.md
```
Contents:
  - Features overview
  - Tech stack details
  - Complete installation steps
  - Database schema explanation
  - Eloquent relations
  - All endpoints listed
  - Authorization details
  - Model methods documentation
  - Usage examples
  - Troubleshooting guide
```

#### INSTALASI.md
```
Contents:
  - Prerequisites list
  - Step-by-step installation
  - Configuration guide
  - Database setup
  - Frontend setup
  - Verification steps
  - Common issues & solutions
  - Production checklist
```

#### QUICKSTART.md
```
Contents:
  - 5-minute quick start
  - Login credentials
  - Feature testing guide
  - Folder structure overview
  - Database tables summary
  - All endpoints list
  - Customization tips
  - Important commands
  - Troubleshooting quick fixes
```

#### TESTING_GUIDE.md
```
Contents:
  - Pre-testing checklist
  - 14+ test cases detailed
  - Role-based access testing
  - QR code operations
  - Attendance scanning
  - Reports functionality
  - Authorization testing
  - Data validation
  - Edge cases
  - Performance testing
  - Browser compatibility
```

#### API_REFERENCE.md
```
Contents:
  - All endpoints documented
  - Request/response examples
  - Query parameters
  - Status codes
  - Error responses
  - Authentication methods
  - cURL examples
  - Rate limiting notes
```

#### RINGKASAN.md
```
Contents:
  - Project summary
  - Complete implementation list
  - Feature overview
  - Tech stack summary
  - File structure
  - Quick installation
  - Default credentials
  - Next steps
  - Production notes
```

---

## File Dependencies

```
User.php
  ├── Role.php (hasMany)
  ├── QrCode.php (hasMany)
  ├── Attendance.php (hasMany)
  └── ActivityLog.php (hasMany)

QrCode.php
  ├── User.php (belongsTo)
  └── Attendance.php (hasMany)

Attendance.php
  ├── User.php (belongsTo)
  └── QrCode.php (belongsTo)

ActivityLog.php
  └── User.php (belongsTo)

Role.php
  └── User.php (hasMany)

Controllers
  ├── Models (all)
  └── Policies (QrCodePolicy, AttendancePolicy)

Middleware
  └── CheckRole uses User.hasRole()

Migrations
  └── Database structure definition

Seeders
  └── Create initial data in migrations

Views
  ├── Layouts (blade templates)
  └── Models (display model data)
```

---

## Quick Reference Table

| File Type | Count | Status |
|-----------|-------|--------|
| Models | 5 | ✅ All created |
| Controllers | 3 | ✅ All created |
| Migrations | 5 | ✅ All created |
| Seeders | 2 | ✅ All created |
| Policies | 2 | ✅ All created |
| Middleware | 1 | ✅ Created |
| Views (Custom) | 6 | ✅ All created |
| Documentation | 6 | ✅ All created |
| Routes | 15+ | ✅ All created |

---

**✅ Complete file structure ready for development!**
