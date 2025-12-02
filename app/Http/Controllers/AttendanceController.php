<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance list.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isManager()) {
            // Managers and admins can see all attendances
            $attendances = Attendance::with('user', 'qrCode')
                ->latest()
                ->paginate(15);
        } else {
            // Employees can only see their own
            $attendances = $user->attendances()
                ->with('qrCode')
                ->latest()
                ->paginate(15);
        }

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Scan QR code to check in/out.
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|string|exists:qr_codes,code',
            'type' => 'required|in:checkin,checkout'
        ]);

        $qrCode = QrCode::where('code', $validated['qr_code'])->first();

        // Validate QR code
        if (!$qrCode->isValid()) {
            return redirect()->back()->with('error', 'QR Code tidak valid atau telah kadaluarsa');
        }

        $type = $validated['type'];
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('attendance_date', $today)
            ->first();

        if ($type === 'checkin') {
            if ($attendance && $attendance->check_in) {
                return redirect()->back()->with('info', 'Anda sudah melakukan check-in hari ini');
            }

            if (!$attendance) {
                // Create new attendance record (check-in)
                $attendance = Attendance::create([
                    'user_id' => auth()->id(),
                    'qr_code_id' => $qrCode->id,
                    'attendance_date' => $today,
                    'check_in' => Carbon::now()->format('H:i:s'),
                    'status' => 'present'
                ]);
            } else {
                // Update check-in time
                $attendance->update(['check_in' => Carbon::now()->format('H:i:s')]);
            }

            ActivityLog::log(
                auth()->id(),
                'qr_scan_checkin',
                'Checked in with QR code',
                'Attendance',
                $attendance->id
            );

            return redirect()->back()->with('success', 'Check-in berhasil dicatat');
        } else { // checkout
            if (!$attendance) {
                return redirect()->back()->with('error', 'Belum ada check-in hari ini. Lakukan check-in terlebih dahulu');
            }

            if ($attendance->check_out) {
                return redirect()->back()->with('info', 'Anda sudah melakukan check-out hari ini');
            }

            // Update check-out time
            $attendance->update(['check_out' => Carbon::now()->format('H:i:s')]);

            ActivityLog::log(
                auth()->id(),
                'qr_scan_checkout',
                'Checked out with QR code',
                'Attendance',
                $attendance->id
            );

            return redirect()->back()->with('success', 'Check-out berhasil dicatat');
        }
    }

    /**
     * Get today's attendance status.
     */
    public function todayStatus()
    {
        $today = Carbon::today();
        $attendance = auth()->user()->attendances()
            ->where('attendance_date', $today)
            ->first();

        return response()->json([
            'status' => $attendance ? $attendance->status : 'absent',
            'check_in' => $attendance?->check_in,
            'check_out' => $attendance?->check_out
        ]);
    }

    /**
     * Manually add attendance (for managers/admins).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Attendance::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i:s',
            'check_out' => 'nullable|date_format:H:i:s',
            'status' => 'required|in:present,absent,late,sick,leave',
            'notes' => 'nullable|string'
        ]);

        $qrCodeId = QrCode::first()->id ?? null;

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'attendance_date' => $validated['attendance_date']
            ],
            [
                'qr_code_id' => $qrCodeId,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'status' => $validated['status'],
                'notes' => $validated['notes']
            ]
        );

        ActivityLog::log(
            auth()->id(),
            'attendance_create',
            'Manually added attendance record',
            'Attendance',
            $attendance->id
        );

        return redirect()->route('attendance.index')->with('success', 'Attendance berhasil ditambahkan');
    }

    /**
     * Delete attendance record.
     */
    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete', $attendance);

        ActivityLog::log(
            auth()->id(),
            'attendance_delete',
            'Deleted attendance record',
            'Attendance',
            $attendance->id
        );

        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance berhasil dihapus');
    }

    /**
     * Admin/Manager scan QR code for employee.
     */
    public function adminScan(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'qr_code' => 'required|string|exists:qr_codes,code',
            'type' => 'required|in:checkin,checkout'
        ]);

        $qrCode = QrCode::where('code', $validated['qr_code'])->first();

        // Validate QR code
        if (!$qrCode->isValid()) {
            return redirect()->back()->with('error', 'QR Code tidak valid atau telah kadaluarsa');
        }

        $employee = $qrCode->user; // Get the employee who owns this QR code
        $type = $validated['type'];
        $today = Carbon::today();
        $attendance = Attendance::where('user_id', $employee->id)
            ->where('attendance_date', $today)
            ->first();

        if ($type === 'checkin') {
            if ($attendance && $attendance->check_in) {
                return redirect()->back()->with('info', $employee->name . ' sudah melakukan check-in hari ini');
            }

            if (!$attendance) {
                // Create new attendance record (check-in)
                $attendance = Attendance::create([
                    'user_id' => $employee->id,
                    'qr_code_id' => $qrCode->id,
                    'attendance_date' => $today,
                    'check_in' => Carbon::now()->format('H:i:s'),
                    'status' => 'present'
                ]);
            } else {
                // Update check-in time
                $attendance->update(['check_in' => Carbon::now()->format('H:i:s')]);
            }

            ActivityLog::log(
                auth()->id(),
                'qr_scan_checkin_admin',
                'Scanned ' . $employee->name . ' check-in with QR code',
                'Attendance',
                $attendance->id
            );

            return redirect()->back()->with('success', 'Check-in untuk ' . $employee->name . ' berhasil dicatat');
        } else { // checkout
            if (!$attendance) {
                return redirect()->back()->with('error', $employee->name . ' belum melakukan check-in hari ini');
            }

            if ($attendance->check_out) {
                return redirect()->back()->with('info', $employee->name . ' sudah melakukan check-out hari ini');
            }

            // Update check-out time
            $attendance->update(['check_out' => Carbon::now()->format('H:i:s')]);

            ActivityLog::log(
                auth()->id(),
                'qr_scan_checkout_admin',
                'Scanned ' . $employee->name . ' check-out with QR code',
                'Attendance',
                $attendance->id
            );

            return redirect()->back()->with('success', 'Check-out untuk ' . $employee->name . ' berhasil dicatat');
        }
    }
}
