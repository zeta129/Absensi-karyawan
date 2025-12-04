<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            👤 {{ $user->name }} - {{ __('Attendance Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern mb-6 animate-fade-in-up">
                <div class="p-6">
                    <div class="mb-6">
                        <a href="{{ route('reports.index') }}" class="text-purple-600 hover:text-purple-800 font-semibold flex items-center gap-2">
                            ← Back to Reports
                        </a>
                    </div>

                    <h2 class="text-2xl font-bold mb-2 text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600 mb-6">📌 NIP: {{ $user->nip ?? 'N/A' }} | 🏢 Department: {{ $user->department ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="card-modern animate-fade-in-up" style="animation-delay: 0s;">
                    <div class="p-6">
                        <p class="text-gray-600 text-sm">✅ Present</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-green-600 to-green-400 bg-clip-text text-transparent">{{ $statistics['present'] }}</p>
                    </div>
                </div>
                <div class="card-modern animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="p-6">
                        <p class="text-gray-600 text-sm">❌ Absent</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-red-600 to-red-400 bg-clip-text text-transparent">{{ $statistics['absent'] }}</p>
                    </div>
                </div>
                <div class="card-modern animate-fade-in-up" style="animation-delay: 0.2s;">
                    <div class="p-6">
                        <p class="text-gray-600 text-sm">⏰ Late</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-yellow-600 to-yellow-400 bg-clip-text text-transparent">{{ $statistics['late'] }}</p>
                    </div>
                </div>
                <div class="card-modern animate-fade-in-up" style="animation-delay: 0.3s;">
                    <div class="p-6">
                        <p class="text-gray-600 text-sm">🏥 Sick</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-orange-400 bg-clip-text text-transparent">{{ $statistics['sick'] }}</p>
                    </div>
                </div>
                <div class="card-modern animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div class="p-6">
                        <p class="text-gray-600 text-sm">🏖️ Leave</p>
                        <p class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-purple-400 bg-clip-text text-transparent">{{ $statistics['leave'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="card-modern animate-fade-in-up" style="animation-delay: 0.5s;">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center gap-2">
                        📋 Attendance Records
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">📅 Date</th>
                                    <th class="px-4 py-3 text-left">⏰ Check In</th>
                                    <th class="px-4 py-3 text-left">🏁 Check Out</th>
                                    <th class="px-4 py-3 text-left">📌 Status</th>
                                    <th class="px-4 py-3 text-left">📝 Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr class="border-b hover:bg-purple-50/50 transition-colors">
                                    <td class="px-4 py-3 text-gray-900">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $attendance->check_in ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ $attendance->check_out ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <x-status-badge :status="$attendance->status" />
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">ℹ️ No records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
