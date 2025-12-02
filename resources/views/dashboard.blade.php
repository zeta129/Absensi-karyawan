<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->isAdmin())
                <!-- ADMIN DASHBOARD -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Admin Dashboard</h3>
                    
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Total Users</div>
                                <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['total_users'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Active Users</div>
                                <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['total_active'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today Present</div>
                                <div class="mt-2 text-3xl font-bold text-purple-600">{{ $stats['today_present'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today Absent</div>
                                <div class="mt-2 text-3xl font-bold text-red-600">{{ $stats['today_absent'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Quick Actions</h4>
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('qr.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    View QR Codes
                                </a>
                                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    View Reports
                                </a>
                                <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                    View Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    @if($recent_activities)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Recent Activities</h4>
                            <div class="space-y-3">
                                @foreach($recent_activities as $activity)
                                <div class="flex justify-between border-b pb-2">
                                    <div>
                                        <span class="font-medium">{{ $activity->user->name }}</span>
                                        <span class="text-sm text-gray-500">{{ $activity->action }}</span>
                                    </div>
                                    <span class="text-sm text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            @elseif(Auth::user()->isManager())
                <!-- MANAGER DASHBOARD -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Manager Dashboard</h3>
                    
                    <!-- Team Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Team Size</div>
                                <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['team_size'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today Present</div>
                                <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['today_present'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today Absent</div>
                                <div class="mt-2 text-3xl font-bold text-red-600">{{ $stats['today_absent'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today Late</div>
                                <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['today_late'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Quick Actions</h4>
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('qr.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Manage QR Codes
                                </a>
                                <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    View Reports
                                </a>
                                <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                                    Team Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Team Attendance Status -->
                    @if($team_attendance)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Team Attendance - Today</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b">
                                        <tr>
                                            <th class="text-left py-2">Name</th>
                                            <th class="text-left py-2">Status</th>
                                            <th class="text-left py-2">Check In</th>
                                            <th class="text-left py-2">Check Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_attendance as $attendance)
                                        <tr class="border-b">
                                            <td class="py-2">{{ $attendance['user']->name }}</td>
                                            <td class="py-2">
                                                <x-status-badge :status="$attendance['status']" />
                                            </td>
                                            <td class="py-2">{{ $attendance['check_in'] ? $attendance['check_in']->format('H:i') : '-' }}</td>
                                            <td class="py-2">{{ $attendance['check_out'] ? $attendance['check_out']->format('H:i') : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            @else
                <!-- EMPLOYEE DASHBOARD -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    
                    <!-- QR Code Section -->
                    @if($activeQrCode)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Your QR Code for Attendance</h4>
                            <div class="flex gap-6 items-center">
                                <div class="border p-4 rounded bg-gray-50">
                                    <img src="{{ $activeQrCode->qr_data }}" alt="QR Code" style="max-width: 200px;" />
                                </div>
                                <div>
                                    <p class="text-gray-600 mb-2">Code: <span class="font-mono font-semibold">{{ $activeQrCode->code }}</span></p>
                                    <p class="text-gray-600 mb-2">Status: <span class="text-green-600 font-semibold">Active</span></p>
                                    @if($activeQrCode->expires_at)
                                    <p class="text-gray-600">Expires: {{ $activeQrCode->expires_at->format('Y-m-d H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800">Anda belum memiliki QR Code. Hubungi manager atau admin untuk membuat QR Code Anda.</p>
                    </div>
                    @endif
                    
                    <!-- Today's Status -->
                    @if($today_attendance)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">Today's Status</div>
                                <div class="mt-2">
                                    <x-status-badge :status="$today_attendance->status" />
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div><span class="text-gray-600">Check In:</span> <span class="font-semibold">{{ $today_attendance->check_in?->format('H:i') ?? '-' }}</span></div>
                                    <div><span class="text-gray-600">Check Out:</span> <span class="font-semibold">{{ $today_attendance->check_out?->format('H:i') ?? '-' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="text-sm font-medium text-gray-500">This Month Summary</div>
                                <div class="mt-4 space-y-2">
                                    <div><span class="text-gray-600">Present:</span> <span class="font-semibold text-green-600">{{ $month_stats['present'] ?? 0 }}</span></div>
                                    <div><span class="text-gray-600">Absent:</span> <span class="font-semibold text-red-600">{{ $month_stats['absent'] ?? 0 }}</span></div>
                                    <div><span class="text-gray-600">Late:</span> <span class="font-semibold text-yellow-600">{{ $month_stats['late'] ?? 0 }}</span></div>
                                    <div><span class="text-gray-600">Total Days:</span> <span class="font-semibold">{{ $month_stats['total_days'] ?? 0 }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800">You haven't checked in today yet. <a href="{{ route('attendance.index') }}" class="font-bold underline">Check in now</a></p>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Quick Actions</h4>
                            <div class="flex gap-2 flex-wrap">
                                <a href="{{ route('attendance.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    My Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    @if($recent_attendance)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h4 class="text-lg font-semibold mb-4">Recent Attendance Records</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="border-b">
                                        <tr>
                                            <th class="text-left py-2">Date</th>
                                            <th class="text-left py-2">Check In</th>
                                            <th class="text-left py-2">Check Out</th>
                                            <th class="text-left py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_attendance as $record)
                                        <tr class="border-b">
                                            <td class="py-2">{{ $record->attendance_date->format('d-m-Y') }}</td>
                                            <td class="py-2">{{ $record->check_in?->format('H:i') ?? '-' }}</td>
                                            <td class="py-2">{{ $record->check_out?->format('H:i') ?? '-' }}</td>
                                            <td class="py-2">
                                                <x-status-badge :status="$record->status" />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
