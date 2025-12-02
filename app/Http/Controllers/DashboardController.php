<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } else {
            return $this->employeeDashboard();
        }
    }

    /**
     * Admin dashboard with full statistics.
     */
    private function adminDashboard()
    {
        $today = Carbon::today();
        
        $stats = [
            'total_users' => User::count(),
            'total_active' => User::count(), // You can add is_active field later
            'today_present' => Attendance::whereDate('attendance_date', $today)
                ->where('status', 'present')->count(),
            'today_absent' => Attendance::whereDate('attendance_date', $today)
                ->where('status', 'absent')->count(),
            'today_late' => Attendance::whereDate('attendance_date', $today)
                ->where('status', 'late')->count(),
            'total_attendances_month' => Attendance::whereMonth('attendance_date', now()->month)
                ->whereYear('attendance_date', now()->year)->count(),
        ];

        $recent_activities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $attendance_this_month = Attendance::selectRaw('DATE(attendance_date) as date, COUNT(*) as count')
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $status_breakdown = Attendance::whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact('stats', 'recent_activities', 'attendance_this_month', 'status_breakdown'));
    }

    /**
     * Manager dashboard with team statistics.
     */
    private function managerDashboard()
    {
        $today = Carbon::today();
        
        // Get team members (employees)
        $team_members = User::where('role_id', 3)->get(); // role_id 3 = employee
        
        $stats = [
            'team_size' => $team_members->count(),
            'today_present' => Attendance::whereDate('attendance_date', $today)
                ->whereIn('user_id', $team_members->pluck('id'))
                ->where('status', 'present')->count(),
            'today_absent' => Attendance::whereDate('attendance_date', $today)
                ->whereIn('user_id', $team_members->pluck('id'))
                ->where('status', 'absent')->count(),
            'today_late' => Attendance::whereDate('attendance_date', $today)
                ->whereIn('user_id', $team_members->pluck('id'))
                ->where('status', 'late')->count(),
        ];

        $team_attendance = $team_members->map(function ($member) use ($today) {
            $attendance = Attendance::where('user_id', $member->id)
                ->whereDate('attendance_date', $today)
                ->first();
            
            return [
                'user' => $member,
                'status' => $attendance?->status ?? 'absent',
                'check_in' => $attendance?->check_in,
                'check_out' => $attendance?->check_out,
            ];
        });

        $recent_activities = ActivityLog::with('user')
            ->whereIn('user_id', $team_members->pluck('id'))
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'team_attendance', 'recent_activities'));
    }

    /**
     * Employee dashboard with personal attendance.
     */
    private function employeeDashboard()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $this_month_start = now()->startOfMonth();
        $this_month_end = now()->endOfMonth();

        // Get employee's active QR codes
        $activeQrCode = \App\Models\QrCode::where('user_id', $user->id)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->first();

        // Today's attendance
        $today_attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        // This month statistics
        $month_stats = [
            'present' => Attendance::where('user_id', $user->id)
                ->whereBetween('attendance_date', [$this_month_start, $this_month_end])
                ->where('status', 'present')->count(),
            'absent' => Attendance::where('user_id', $user->id)
                ->whereBetween('attendance_date', [$this_month_start, $this_month_end])
                ->where('status', 'absent')->count(),
            'late' => Attendance::where('user_id', $user->id)
                ->whereBetween('attendance_date', [$this_month_start, $this_month_end])
                ->where('status', 'late')->count(),
            'total_days' => Attendance::where('user_id', $user->id)
                ->whereBetween('attendance_date', [$this_month_start, $this_month_end])
                ->count(),
        ];

        // Recent attendance records
        $recent_attendance = Attendance::where('user_id', $user->id)
            ->latest('attendance_date')
            ->limit(7)
            ->get();

        return view('dashboard', compact('user', 'today_attendance', 'month_stats', 'recent_attendance', 'activeQrCode'));
    }
}
