<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display attendance report.
     */
    public function index(Request $request)
    {
        // Check if user is admin or manager
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $monthYear = $request->get('month_year', now()->format('Y-m'));
        $userId = $request->get('user_id');

        $query = Attendance::query();

        // Filter by month
        if ($monthYear) {
            $query->whereYear('attendance_date', substr($monthYear, 0, 4))
                  ->whereMonth('attendance_date', substr($monthYear, 5, 2));
        }

        // Filter by user if not admin
        if (auth()->user()->isEmployee()) {
            $query->where('user_id', auth()->id());
        } elseif ($userId) {
            $query->where('user_id', $userId);
        }

        $attendances = $query->with('user')->latest()->paginate(20);
        
        $users = auth()->user()->isAdmin() ? User::all() : collect();

        $summary = $this->generateSummary($monthYear);

        return view('reports.index', compact('attendances', 'users', 'summary', 'monthYear'));
    }

    /**
     * Generate attendance summary.
     */
    private function generateSummary($monthYear)
    {
        $year = substr($monthYear, 0, 4);
        $month = substr($monthYear, 5, 2);

        $statuses = Attendance::whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->groupBy('status')
            ->selectRaw('status, COUNT(*) as count')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'present' => $statuses['present'] ?? 0,
            'absent' => $statuses['absent'] ?? 0,
            'late' => $statuses['late'] ?? 0,
            'sick' => $statuses['sick'] ?? 0,
            'leave' => $statuses['leave'] ?? 0,
            'total' => array_sum($statuses)
        ];
    }

    /**
     * Export report to CSV.
     */
    public function export(Request $request)
    {
        // Check if user is admin or manager
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $monthYear = $request->get('month_year', now()->format('Y-m'));

        $query = Attendance::with('user');

        if ($monthYear) {
            $query->whereYear('attendance_date', substr($monthYear, 0, 4))
                  ->whereMonth('attendance_date', substr($monthYear, 5, 2));
        }

        if (auth()->user()->isEmployee()) {
            $query->where('user_id', auth()->id());
        }

        $attendances = $query->get();

        $csv = "NIP,Nama,Tanggal,Check In,Check Out,Status,Catatan\n";
        
        foreach ($attendances as $attendance) {
            $csv .= implode(',', [
                $attendance->user->nip,
                $attendance->user->name,
                $attendance->attendance_date->format('Y-m-d'),
                $attendance->check_in ?? '-',
                $attendance->check_out ?? '-',
                $attendance->status,
                '"' . ($attendance->notes ?? '') . '"'
            ]) . "\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="attendance_report_' . $monthYear . '.csv"');
    }

    /**
     * Display user attendance detail.
     */
    public function userDetail(User $user, Request $request)
    {
        // Check if user is viewing own data or is admin/manager
        if (auth()->id() !== $user->id && !auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $monthYear = $request->get('month_year', now()->format('Y-m'));

        $attendances = $user->attendances()
            ->whereYear('attendance_date', substr($monthYear, 0, 4))
            ->whereMonth('attendance_date', substr($monthYear, 5, 2))
            ->latest()
            ->get();

        $statistics = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
        ];

        return view('reports.user-detail', compact('user', 'attendances', 'statistics', 'monthYear'));
    }

    /**
     * Display activity logs.
     */
    public function activityLog(Request $request)
    {
        // Check if user is admin or manager
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $query = ActivityLog::with('user');

        if (auth()->user()->isEmployee()) {
            $query->where('user_id', auth()->id());
        }

        $logs = $query->latest()->paginate(20);

        return view('reports.activity-log', compact('logs'));
    }
}
