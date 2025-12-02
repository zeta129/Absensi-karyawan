# 🧪 TESTING GUIDE - Sistem Absensi QR Code

## Pre-Testing Checklist

Pastikan sudah:
- ✅ Migrate database: `php artisan migrate`
- ✅ Seed data: `php artisan db:seed`
- ✅ Build assets: `npm run build`
- ✅ Run server: `php artisan serve`

---

## Test Case 1: Login & Role Verification

### Scenario: Test Login dengan 3 Role Berbeda

**Admin User**
```
Email: admin@absensi.local
Password: password123
Expected: Redirect ke dashboard, full menu access
```

Steps:
1. Open http://localhost:8000
2. Click Login
3. Enter admin credentials
4. ✅ Verify: Dapat akses semua menu (QR, Attendance, Reports)
5. ✅ Verify: Admin tag di navbar

**Manager User**
```
Email: manager@absensi.local
Password: password123
Expected: Dashboard dengan limited menu
```

Steps:
1. Login dengan manager credentials
2. ✅ Verify: Dapat akses QR Code, Attendance, Reports
3. ✅ Verify: TIDAK bisa akses admin-only features

**Employee User**
```
Email: employee1@absensi.local
Password: password123
Expected: Dashboard dengan minimal menu
```

Steps:
1. Login dengan employee credentials
2. ✅ Verify: HANYA bisa akses Attendance & Profile
3. ✅ Verify: TIDAK ada menu QR Code atau Reports

---

## Test Case 2: QR Code Generation

### Scenario: Admin/Manager Generate QR Code

**Prerequisites**:
- Login sebagai admin atau manager

**Steps**:
1. Go to menu → QR Code Management
2. Click "Generate QR Code" button
3. (Optional) Select expiration date
4. Click "Generate"
5. ✅ Verify: QR code muncul di list
6. ✅ Verify: Code format: `QR_[user_id]_[timestamp]`
7. ✅ Verify: QR code image visible
8. ✅ Verify: Status = "Active"
9. ✅ Verify: Generated date correct

**Expected Result**: ✅ QR code berhasil dibuat

---

## Test Case 3: QR Code Operations

### Download QR Code

**Steps**:
1. Di QR Code list, click "Download" button
2. File should download: `qr_QR_[code].png`
3. ✅ Verify: File exist dan bisa dibuka
4. ✅ Verify: QR code image valid

### View QR Code Detail

**Steps**:
1. Click "View" button pada QR code
2. ✅ Verify: Page menampilkan:
   - QR code image besar
   - Code value
   - Generated date/time
   - Expiration date (if set)
   - Status (Active/Inactive)
   - Total scans count
   - Recent scans table

### Deactivate QR Code

**Steps**:
1. Click "Deactivate" button
2. Confirm action
3. ✅ Verify: Status berubah menjadi "Inactive"
4. ✅ Verify: Button hilang setelah deactivate

**Expected Result**: ✅ QR code tidak bisa di-scan lagi

---

## Test Case 4: Attendance - Employee Scan QR Code

### Scenario: Employee Check-In via QR Scan

**Prerequisites**:
- Login sebagai employee1@absensi.local
- Sudah ada active QR code
- Belum ada attendance record hari ini

**Steps**:
1. Go to menu → Attendance
2. Lihat form "Scan QR Code"
3. Copy QR code value dari QR management (buka tab baru)
4. Paste ke input field
5. Press Enter atau click button
6. ✅ Verify: Success message "Check-in berhasil dicatat"
7. ✅ Verify: Attendance record appear di table:
   - Date: today
   - Check In: current time
   - Check Out: empty
   - Status: "Present"

### Scenario: Employee Check-Out via QR Scan

**Steps**:
1. Scan QR code lagi di form yang sama
2. ✅ Verify: Success message "Check-out berhasil dicatat"
3. ✅ Verify: Same attendance record updated:
   - Check Out: current time
   - Status: still "Present"

**Expected Result**: ✅ Check-in dan Check-out berhasil tercatat

---

## Test Case 5: Invalid QR Code Scan

### Scenario: Scan Invalid/Expired QR Code

**Steps**:
1. Deactivate QR code (dari admin panel)
2. Login sebagai employee
3. Try scan deactivated QR code
4. ✅ Verify: Error message "QR Code tidak valid atau telah kadaluarsa"
5. ✅ Verify: No attendance record created

**Expected Result**: ✅ Invalid QR rejected

### Scenario: Scan dengan QR Code yang Sudah Expired

**Steps**:
1. Generate QR code dengan expiration date kemarin
2. Try scan as employee
3. ✅ Verify: Error message about expiration
4. ✅ Verify: No attendance record

**Expected Result**: ✅ Expired QR rejected

---

## Test Case 6: Attendance List - Role-Based Access

### Admin/Manager View All Attendances

**Steps**:
1. Login sebagai admin/manager
2. Go to Attendance menu
3. ✅ Verify: Table show attendance dari ALL users
4. ✅ Verify: Action buttons visible (Delete)
5. ✅ Verify: Pagination working

### Employee View Own Attendance Only

**Steps**:
1. Login sebagai employee
2. Go to Attendance menu
3. ✅ Verify: ONLY own records visible
4. ✅ Verify: Other employee records NOT visible
5. ✅ Verify: No Delete button

**Expected Result**: ✅ Access control working

---

## Test Case 7: Manual Attendance Entry

### Scenario: Manager Manually Add Attendance

**Prerequisites**:
- Login sebagai manager
- Go to Attendance page

**Steps**:
1. Klik "Add Attendance" button (if available)
2. Fill form:
   - User: Select employee
   - Date: Select date
   - Check In: 08:00:00
   - Check Out: 17:00:00
   - Status: Select "Present"
   - Notes: Optional notes
3. Submit form
4. ✅ Verify: Success message
5. ✅ Verify: Record appear di attendance list
6. ✅ Verify: Status = "Present"

### Delete Attendance Entry

**Steps**:
1. Klik "Delete" button pada attendance record
2. Confirm delete
3. ✅ Verify: Success message
4. ✅ Verify: Record removed dari table

**Expected Result**: ✅ Manual entry & deletion working

---

## Test Case 8: Reports & Analytics

### Attendance Summary Report

**Prerequisites**:
- Login sebagai admin/manager
- Multiple attendance records exist

**Steps**:
1. Go to menu → Reports
2. ✅ Verify: Page show:
   - Month/Year filter
   - User filter (admin only)
   - Summary statistics cards:
     - Present count
     - Absent count
     - Late count
     - Sick count
     - Leave count
     - Total count
   - Attendance records table
3. Filter by different month
4. ✅ Verify: Data updated accordingly
5. Filter by user (if admin)
6. ✅ Verify: Filtered correctly

### Export to CSV

**Steps**:
1. Click "Export CSV" button
2. File download: `attendance_report_[YYYY-MM].csv`
3. ✅ Verify: File valid & can open in Excel
4. ✅ Verify: Data format correct:
   ```
   NIP,Nama,Tanggal,Check In,Check Out,Status,Catatan
   EMP001,Employee 1,2025-12-01,08:00:00,17:00:00,Present,
   ```

### User Detail Report

**Steps**:
1. Click on user name di attendance table
2. ✅ Verify: User detail page show:
   - User info (name, NIP, department)
   - Statistics for month:
     - Present, Absent, Late, Sick, Leave counts
   - Full attendance records untuk user
   - Month selector untuk view different months

**Expected Result**: ✅ Reports working correctly

---

## Test Case 9: Activity Logs

### View Activity Logs

**Prerequisites**:
- Login sebagai admin/manager

**Steps**:
1. Go to menu → Reports → Activity Logs
2. ✅ Verify: Table show activities:
   - User name
   - Action (login, logout, qr_generate, qr_scan, etc)
   - Description
   - IP address
   - Timestamp
3. ✅ Verify: Multiple actions logged
4. ✅ Verify: Pagination working

### Check Log for QR Generation

**Steps**:
1. Generate QR code sebagai admin
2. Go to Activity Logs
3. ✅ Verify: Log entry exist:
   - Action: "qr_generate"
   - Description: "Generated new QR code"
   - Timestamp: recent

### Check Log for Attendance Scan

**Steps**:
1. Scan QR code sebagai employee
2. Go to Reports → Activity Logs (as admin/manager)
3. ✅ Verify: Log entries exist:
   - Action: "qr_scan_checkin" or "qr_scan_checkout"
   - User: employee name

**Expected Result**: ✅ All activities logged correctly

---

## Test Case 10: Authorization & Security

### Test Unauthorized Access

**Steps**:
1. Login sebagai employee
2. Try access URL: `/qr-codes`
3. ✅ Verify: 403 Forbidden error
4. Try access URL: `/reports`
5. ✅ Verify: 403 Forbidden error
6. Try direct URL: `/qr-codes/1/download`
7. ✅ Verify: 403 Forbidden error

### Test CSRF Protection

**Steps**:
1. Check any form (Generate QR, Manual Attendance)
2. ✅ Verify: Form contains `@csrf` token
3. Try submit form without token (using curl/postman)
4. ✅ Verify: 419 Unsupported Media Type error

**Expected Result**: ✅ Authorization & CSRF working

---

## Test Case 11: Data Validation

### Test QR Generation with Invalid Expiry

**Steps**:
1. Generate QR code dengan past date
2. ✅ Verify: Error message atau validation
3. Or generate with future date
4. ✅ Verify: Success & expiry set correctly

### Test Manual Attendance Entry - Invalid Data

**Steps**:
1. Try manual entry dengan:
   - Missing required fields
   - Check Out before Check In
   - Invalid date format
   - Duplicate attendance same day
2. ✅ Verify: Validation error messages shown

**Expected Result**: ✅ Data validation working

---

## Test Case 12: Edge Cases

### Same User Check-In Twice Without Check-Out

**Prerequisites**:
- Login sebagai employee
- Scan QR code (check-in exists)

**Steps**:
1. Try scan QR code again
2. ✅ Verify: Warning message "Anda sudah melakukan check-in hari ini"
3. ✅ Verify: No duplicate record created

### Check-Out Without Check-In

**Steps**:
1. Manually create attendance dengan ONLY check-out
2. ✅ Verify: Record created (if allowed by validation)
3. Or verify error if not allowed

### Delete QR Code After Scan

**Steps**:
1. Scan QR code (attendance created)
2. Deactivate QR code
3. ✅ Verify: Attendance record still exist
4. ✅ Verify: Future scan impossible

**Expected Result**: ✅ Edge cases handled properly

---

## Test Case 13: Performance Testing

### Large Dataset

**Steps**:
1. Add 100+ attendance records manually
2. Go to Reports page
3. ✅ Verify: Page load within reasonable time (<2 seconds)
4. Pagination working smooth
5. Filter working responsive
6. Export CSV successful

### Large QR Code List

**Steps**:
1. Generate 50+ QR codes
2. View QR code list
3. ✅ Verify: Page load fast
4. Pagination working

**Expected Result**: ✅ Performance acceptable

---

## Test Case 14: Responsive Design

### Desktop View
```
✅ All UI elements visible
✅ Tables formatted correctly
✅ Forms aligned properly
✅ Navigation working
```

### Mobile View (Simulated)
```
✅ Responsive design adapt
✅ QR scanner form visible
✅ Touch-friendly buttons
✅ Readable text
```

---

## Browser Compatibility Test

| Browser | Status |
|---------|--------|
| Chrome 120+ | ✅ Test |
| Firefox 121+ | ✅ Test |
| Safari 17+ | ✅ Test |
| Edge 120+ | ✅ Test |

---

## Final Verification Checklist

- [ ] All 3 roles login successful
- [ ] QR code generation working
- [ ] QR code download working
- [ ] Employee scan QR check-in/out
- [ ] Attendance list showing correct data
- [ ] Manual attendance entry working
- [ ] Reports generating correctly
- [ ] CSV export successful
- [ ] Activity logs recording
- [ ] Unauthorized access blocked
- [ ] CSRF protection active
- [ ] Data validation working
- [ ] Edge cases handled
- [ ] Performance acceptable
- [ ] UI responsive

---

## Bugs Found Template

```
**Bug #X: [Title]**

Component: [QR/Attendance/Reports]
Severity: [Low/Medium/High]
Steps to Reproduce:
1. ...
2. ...

Expected: 
Actual: 
Screenshot: 

```

---

## Sign-Off

All tests passed: ___________
Date: ___________
Tester: ___________

---

**Ready for Production! ✅**
