# 📡 API REFERENCE - Sistem Absensi QR Code

## Base URL
```
http://localhost:8000/api (if API routes added)
http://localhost:8000    (Web routes)
```

---

## Authentication

### Login
```http
POST /login
Content-Type: application/x-www-form-urlencoded

email=admin@absensi.local
password=password123
```

**Response (Success)**:
```json
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@absensi.local",
  "role_id": 1,
  "nip": "ADM001"
}
```

### Logout
```http
POST /logout
```

### Register
```http
POST /register
Content-Type: application/x-www-form-urlencoded

name=John Doe
email=john@example.com
password=password123
password_confirmation=password123
```

---

## QR Code Endpoints

### List QR Codes
```http
GET /qr-codes
Authorization: Bearer {token}
```

**Query Parameters**:
```
page=1          # Pagination
sort=created_at # Sort field
order=desc      # asc/desc
```

**Response**:
```json
{
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "code": "QR_1_635fb234",
      "qr_data": "data:image/png;base64,...",
      "generated_at": "2025-12-01T10:30:00Z",
      "expires_at": "2025-12-08T23:59:59Z",
      "is_active": true,
      "created_at": "2025-12-01T10:30:00Z",
      "updated_at": "2025-12-01T10:30:00Z"
    }
  ],
  "links": { "first": "...", "last": "...", "next": "..." },
  "meta": { "current_page": 1, "total": 10 }
}
```

### Generate QR Code
```http
POST /qr-codes/generate
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {token}

expires_at=2025-12-08
```

**Response (Success - 201)**:
```json
{
  "message": "QR Code berhasil dibuat",
  "data": {
    "id": 5,
    "user_id": 1,
    "code": "QR_1_635fb890",
    "qr_data": "data:image/png;base64,...",
    "generated_at": "2025-12-01T15:45:00Z",
    "expires_at": "2025-12-08T23:59:59Z",
    "is_active": true
  }
}
```

**Response (Error - 422)**:
```json
{
  "message": "The expires_at must be after or equal to today.",
  "errors": { "expires_at": ["..."] }
}
```

### View QR Code Detail
```http
GET /qr-codes/{id}
Authorization: Bearer {token}
```

**Response**:
```json
{
  "qr_code": {
    "id": 1,
    "code": "QR_1_635fb234",
    "generated_at": "2025-12-01T10:30:00Z",
    "expires_at": "2025-12-08T23:59:59Z",
    "is_active": true,
    "is_valid": true,
    "scan_count": 25
  },
  "recent_scans": [
    {
      "id": 45,
      "user_id": 3,
      "user_name": "Employee 1",
      "attendance_date": "2025-12-01",
      "check_in": "08:15:00",
      "check_out": "17:30:00",
      "status": "present"
    }
  ]
}
```

### Download QR Code
```http
GET /qr-codes/{id}/download
Authorization: Bearer {token}
```

**Response**:
- Content-Type: image/png
- File: `qr_QR_1_635fb234.png`

### Deactivate QR Code
```http
POST /qr-codes/{id}/deactivate
Authorization: Bearer {token}
```

**Response (Success)**:
```json
{
  "message": "QR Code telah dinonaktifkan"
}
```

---

## Attendance Endpoints

### List Attendance
```http
GET /attendance
Authorization: Bearer {token}
```

**Query Parameters**:
```
page=1              # Pagination
status=present      # Filter by status
user_id=3           # Filter by user (admin/manager only)
date_from=2025-12-01
date_to=2025-12-31
```

**Response**:
```json
{
  "data": [
    {
      "id": 101,
      "user_id": 3,
      "user_name": "Employee 1",
      "qr_code_id": 5,
      "attendance_date": "2025-12-01",
      "check_in": "08:15:00",
      "check_out": "17:30:00",
      "status": "present",
      "notes": null,
      "created_at": "2025-12-01T08:15:00Z"
    }
  ],
  "meta": { "current_page": 1, "total": 156 }
}
```

### Scan QR Code (Check-In/Out)
```http
POST /attendance/scan
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {token}

qr_code=QR_1_635fb890
```

**Response (Check-In Success)**:
```json
{
  "message": "Check-in berhasil dicatat",
  "data": {
    "id": 202,
    "attendance_date": "2025-12-01",
    "check_in": "08:20:00",
    "check_out": null,
    "status": "present"
  }
}
```

**Response (Check-Out Success)**:
```json
{
  "message": "Check-out berhasil dicatat",
  "data": {
    "id": 202,
    "attendance_date": "2025-12-01",
    "check_in": "08:20:00",
    "check_out": "17:45:00",
    "status": "present"
  }
}
```

**Response (Invalid QR)**:
```json
{
  "message": "QR Code tidak valid atau telah kadaluarsa",
  "status": 400
}
```

### Get Today Status
```http
GET /attendance/today-status
Authorization: Bearer {token}
```

**Response**:
```json
{
  "status": "present",
  "check_in": "08:15:00",
  "check_out": "17:30:00"
}
```

Or if no record:
```json
{
  "status": "absent",
  "check_in": null,
  "check_out": null
}
```

### Add Attendance Manually
```http
POST /attendance
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {token}

user_id=5
attendance_date=2025-12-01
check_in=08:00:00
check_out=17:00:00
status=present
notes=Terlambat karena lalu lintas
```

**Response (Success - 201)**:
```json
{
  "message": "Attendance berhasil ditambahkan",
  "data": {
    "id": 205,
    "user_id": 5,
    "attendance_date": "2025-12-01",
    "check_in": "08:00:00",
    "check_out": "17:00:00",
    "status": "present",
    "notes": "Terlambat karena lalu lintas"
  }
}
```

**Response (Error - 422)**:
```json
{
  "message": "The given data was invalid",
  "errors": {
    "user_id": ["The user id field is required."],
    "status": ["The status must be one of: present, absent, late, sick, leave."]
  }
}
```

### Delete Attendance
```http
DELETE /attendance/{id}
Authorization: Bearer {token}
```

**Response (Success - 200)**:
```json
{
  "message": "Attendance berhasil dihapus"
}
```

---

## Reports Endpoints

### Attendance Summary Report
```http
GET /reports
Authorization: Bearer {token}
```

**Query Parameters**:
```
month_year=2025-12  # Required YYYY-MM format
user_id=3           # Optional, admin/manager only
```

**Response**:
```json
{
  "summary": {
    "present": 20,
    "absent": 1,
    "late": 2,
    "sick": 1,
    "leave": 1,
    "total": 25
  },
  "records": [
    {
      "id": 101,
      "user": { "id": 3, "name": "Employee 1", "nip": "EMP001" },
      "attendance_date": "2025-12-01",
      "check_in": "08:15:00",
      "check_out": "17:30:00",
      "status": "present"
    }
  ],
  "pagination": { "current_page": 1, "total": 45 }
}
```

### Export to CSV
```http
GET /reports/export
Authorization: Bearer {token}
```

**Query Parameters**:
```
month_year=2025-12  # YYYY-MM format
user_id=3           # Optional
```

**Response**:
- Content-Type: text/csv
- Filename: `attendance_report_2025-12.csv`

**CSV Format**:
```csv
NIP,Nama,Tanggal,Check In,Check Out,Status,Catatan
EMP001,Employee 1,2025-12-01,08:15:00,17:30:00,Present,
EMP002,Employee 2,2025-12-01,08:00:00,17:00:00,Present,Late morning
```

### User Attendance Detail
```http
GET /reports/user/{user_id}
Authorization: Bearer {token}
```

**Query Parameters**:
```
month_year=2025-12  # Optional
```

**Response**:
```json
{
  "user": {
    "id": 3,
    "name": "Employee 1",
    "nip": "EMP001",
    "email": "employee1@absensi.local",
    "department": "Operations"
  },
  "statistics": {
    "present": 20,
    "absent": 1,
    "late": 2,
    "sick": 1,
    "leave": 1
  },
  "records": [
    {
      "id": 101,
      "attendance_date": "2025-12-01",
      "check_in": "08:15:00",
      "check_out": "17:30:00",
      "status": "present",
      "notes": null
    }
  ]
}
```

### Activity Logs
```http
GET /reports/activity-log
Authorization: Bearer {token}
```

**Query Parameters**:
```
page=1          # Pagination
user_id=1       # Filter by user
action=login    # Filter by action
```

**Response**:
```json
{
  "data": [
    {
      "id": 501,
      "user_id": 1,
      "user_name": "Admin User",
      "action": "qr_generate",
      "description": "Generated new QR code",
      "model_type": "QrCode",
      "model_id": 5,
      "ip_address": "127.0.0.1",
      "user_agent": "Mozilla/5.0...",
      "created_at": "2025-12-01T15:45:00Z"
    }
  ],
  "pagination": { "current_page": 1, "total": 250 }
}
```

---

## User Profile Endpoints

### Get Profile
```http
GET /profile
Authorization: Bearer {token}
```

**Response**:
```json
{
  "user": {
    "id": 3,
    "name": "Employee 1",
    "email": "employee1@absensi.local",
    "role": {
      "id": 3,
      "name": "employee"
    },
    "nip": "EMP001",
    "phone": "081234567891",
    "department": "Operations",
    "email_verified_at": "2025-12-01T08:00:00Z"
  }
}
```

### Update Profile
```http
PATCH /profile
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {token}

name=Employee One
phone=081234567892
```

**Response**:
```json
{
  "message": "Profile updated successfully"
}
```

### Update Password
```http
PUT /profile/password
Content-Type: application/x-www-form-urlencoded
Authorization: Bearer {token}

current_password=password123
password=new_password
password_confirmation=new_password
```

**Response**:
```json
{
  "message": "Password updated successfully"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized"
}
```

### 404 Not Found
```json
{
  "message": "Record not found"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Server Error
```json
{
  "message": "Server error",
  "error": "Detailed error message"
}
```

---

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created |
| 400 | Bad Request - Invalid data |
| 401 | Unauthorized - Not authenticated |
| 403 | Forbidden - No permission |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Server Error - Something went wrong |

---

## Rate Limiting

Currently no rate limiting implemented. Recommended to add for production:
```
- 60 requests per minute per IP
- 1000 requests per hour per user
```

---

## Authentication Methods

### Session Cookie
Default method used by web routes:
```http
Cookie: XSRF-TOKEN=...; Laravel_session=...
```

### Bearer Token (untuk API di future)
```http
Authorization: Bearer {token}
```

---

## CORS Headers

Currently configured for same-origin only. For cross-origin requests, configure:
```php
'cors' => [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
],
```

---

## Webhook Integration Ready

For future enhancement, can add webhooks:
```php
- attendance.created
- attendance.updated
- qr_code.generated
- qr_code.scanned
- user.logged_in
```

---

## API Testing Tools

### cURL Examples

**Login**:
```bash
curl -X POST http://localhost:8000/login \
  -d "email=admin@absensi.local&password=password123"
```

**Get Attendance**:
```bash
curl -X GET http://localhost:8000/attendance \
  -H "Authorization: Bearer {token}"
```

**Scan QR Code**:
```bash
curl -X POST http://localhost:8000/attendance/scan \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "qr_code=QR_1_635fb234"
```

### Postman Collection

Import to Postman:
```json
{
  "info": { "name": "Absensi QR Code API" },
  "item": [
    {
      "name": "Login",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/login"
      }
    }
  ]
}
```

---

## Future API Enhancements

- [ ] Add JWT authentication
- [ ] REST API for mobile app
- [ ] GraphQL endpoint
- [ ] Webhook support
- [ ] Rate limiting
- [ ] API versioning (v1, v2)
- [ ] OAuth2 support

---

**API Documentation Complete ✅**
