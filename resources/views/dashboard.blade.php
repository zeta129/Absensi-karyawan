<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            ✨ {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->isAdmin())
                <!-- ADMIN DASHBOARD -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-4">👨‍💼 Admin Dashboard</h3>
                    
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">👥 Total Users</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">{{ $stats['total_users'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.1s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">✅ Active Users</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">{{ $stats['total_active'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.2s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">📊 Today Present</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">{{ $stats['today_present'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.3s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">❌ Today Absent</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-red-600 to-red-400 bg-clip-text text-transparent">{{ $stats['today_absent'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card-modern mb-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">⚡ Quick Actions</h4>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('qr.index') }}" class="btn-primary">
                                    📱 View QR Codes
                                </a>
                                <a href="{{ route('reports.index') }}" class="btn-success">
                                    📊 View Reports
                                </a>
                                <a href="{{ route('attendance.index') }}" class="btn-secondary">
                                    ✅ View Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    @if($recent_activities)
                    <div class="card-modern animate-fade-in-up" style="animation-delay: 0.5s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">📋 Recent Activities</h4>
                            <div class="space-y-3">
                                @foreach($recent_activities as $activity)
                                <div class="flex justify-between border-b border-gray-200 pb-2 last:border-b-0">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $activity->user->name }}</span>
                                        <span class="text-sm text-gray-600 block">{{ $activity->action }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
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
                    <h3 class="text-2xl font-bold text-white mb-4">👔 Manager Dashboard</h3>
                    
                    <!-- Team Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">👥 Team Size</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">{{ $stats['team_size'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.1s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">✅ Today Present</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">{{ $stats['today_present'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.2s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">❌ Today Absent</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-red-600 to-red-400 bg-clip-text text-transparent">{{ $stats['today_absent'] ?? 0 }}</div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.3s;">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-600">⏰ Today Late</div>
                                <div class="mt-2 text-3xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-400 bg-clip-text text-transparent">{{ $stats['today_late'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card-modern mb-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">⚡ Quick Actions</h4>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('qr.index') }}" class="btn-primary">
                                    📱 Manage QR Codes
                                </a>
                                <a href="{{ route('reports.index') }}" class="btn-success">
                                    📊 View Reports
                                </a>
                                <a href="{{ route('attendance.index') }}" class="btn-secondary">
                                    ✅ Team Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Team Attendance Status -->
                    @if($team_attendance)
                    <div class="card-modern animate-fade-in-up" style="animation-delay: 0.5s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">👥 Team Attendance - Today</h4>
                            <div class="overflow-x-auto">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th class="text-left py-3 px-4">👤 Name</th>
                                            <th class="text-left py-3 px-4">📌 Status</th>
                                            <th class="text-left py-3 px-4">⏰ Check In</th>
                                            <th class="text-left py-3 px-4">🏁 Check Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_attendance as $attendance)
                                        <tr class="border-b hover:bg-purple-50/50 transition-colors">
                                            <td class="py-3 px-4 text-gray-900">{{ $attendance['user']->name }}</td>
                                            <td class="py-3 px-4">
                                                <x-status-badge :status="$attendance['status']" />
                                            </td>
                                            <td class="py-3 px-4 text-gray-900">{{ $attendance['check_in'] ? $attendance['check_in']->format('H:i') : '-' }}</td>
                                            <td class="py-3 px-4 text-gray-900">{{ $attendance['check_out'] ? $attendance['check_out']->format('H:i') : '-' }}</td>
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
                    <h3 class="text-2xl font-bold text-white mb-4">👋 Welcome, {{ Auth::user()->name }}!</h3>
                    
                    <!-- QR Code Section -->
                    @if($activeQrCode)
                    <div class="card-modern mb-6 animate-fade-in-up" style="animation-delay: 0s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">🔳 Your QR Code for Attendance</h4>
                            <div class="flex gap-6 items-center">
                                <div class="border-2 border-purple-300 p-4 rounded-lg bg-white">
                                    <img src="{{ $activeQrCode->qr_data }}" alt="QR Code" style="max-width: 200px;" />
                                </div>
                                <div>
                                    <p class="text-gray-700 mb-2">📌 <span class="font-semibold">Code:</span> <span class="font-mono font-bold text-purple-600">{{ $activeQrCode->code }}</span></p>
                                    <p class="text-gray-700 mb-2">✅ <span class="font-semibold">Status:</span> <span class="text-green-600 font-bold">Active</span></p>
                                    @if($activeQrCode->expires_at)
                                    <p class="text-gray-700">⏰ <span class="font-semibold">Expires:</span> {{ $activeQrCode->expires_at->format('Y-m-d H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert-modern" style="--alert-type: warning;">
                        <p class="text-yellow-800 flex items-center gap-2">⚠️ Anda belum memiliki QR Code. Hubungi manager atau admin untuk membuat QR Code Anda.</p>
                    </div>
                    @endif
                    
                    <!-- Today's Status -->
                    @if($today_attendance)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.1s;">
                            <div class="p-6">
                                <div class="text-sm font-semibold text-gray-600 flex items-center gap-2">📊 Today's Status</div>
                                <div class="mt-4">
                                    <x-status-badge :status="$today_attendance->status" />
                                </div>
                                <div class="mt-4 space-y-2">
                                    <div class="text-gray-700">⏰ <span class="text-gray-600">Check In:</span> <span class="font-semibold">{{ $today_attendance->check_in?->format('H:i') ?? '-' }}</span></div>
                                    <div class="text-gray-700">🏁 <span class="text-gray-600">Check Out:</span> <span class="font-semibold">{{ $today_attendance->check_out?->format('H:i') ?? '-' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="card-modern animate-fade-in-up" style="animation-delay: 0.2s;">
                            <div class="p-6">
                                <div class="text-sm font-semibold text-gray-600 flex items-center gap-2">📅 This Month Summary</div>
                                <div class="mt-4 space-y-2">
                                    <div class="text-gray-700">✅ <span class="text-gray-600">Present:</span> <span class="font-semibold text-green-600">{{ $month_stats['present'] ?? 0 }}</span></div>
                                    <div class="text-gray-700">❌ <span class="text-gray-600">Absent:</span> <span class="font-semibold text-red-600">{{ $month_stats['absent'] ?? 0 }}</span></div>
                                    <div class="text-gray-700">⏰ <span class="text-gray-600">Late:</span> <span class="font-semibold text-yellow-600">{{ $month_stats['late'] ?? 0 }}</span></div>
                                    <div class="text-gray-700">📆 <span class="text-gray-600">Total Days:</span> <span class="font-semibold">{{ $month_stats['total_days'] ?? 0 }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert-modern" style="--alert-type: warning;">
                        <p class="text-yellow-800 flex items-center gap-2">ℹ️ You haven't checked in today yet. <a href="{{ route('attendance.index') }}" class="font-bold underline hover:no-underline">Check in now</a></p>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="card-modern mb-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">⚡ Quick Actions</h4>
                            <div class="flex gap-3 flex-wrap">
                                <a href="{{ route('attendance.index') }}" class="btn-primary">
                                    📱 My Attendance
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Attendance -->
                    @if($recent_attendance)
                    <div class="card-modern animate-fade-in-up" style="animation-delay: 0.4s;">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">📋 Recent Attendance Records</h4>
                            <div class="overflow-x-auto">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th class="text-left py-3 px-4">📅 Date</th>
                                            <th class="text-left py-3 px-4">⏰ Check In</th>
                                            <th class="text-left py-3 px-4">🏁 Check Out</th>
                                            <th class="text-left py-3 px-4">📌 Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_attendance as $record)
                                        <tr class="border-b hover:bg-purple-50/50 transition-colors">
                                            <td class="py-3 px-4 text-gray-900">{{ $record->attendance_date->format('d-m-Y') }}</td>
                                            <td class="py-3 px-4 text-gray-900">{{ $record->check_in?->format('H:i') ?? '-' }}</td>
                                            <td class="py-3 px-4 text-gray-900">{{ $record->check_out?->format('H:i') ?? '-' }}</td>
                                            <td class="py-3 px-4">
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
