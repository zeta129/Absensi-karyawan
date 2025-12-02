<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }} - {{ __('Attendance Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('reports.index') }}" class="text-blue-500 hover:text-blue-700">&larr; Back to Reports</a>
                    </div>

                    <h2 class="text-2xl font-bold mb-2">{{ $user->name }}</h2>
                    <p class="text-gray-600 mb-6">NIP: {{ $user->nip ?? 'N/A' }} | Department: {{ $user->department ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Present</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statistics['present'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Absent</p>
                        <p class="text-2xl font-bold text-red-600">{{ $statistics['absent'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Late</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $statistics['late'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Sick</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $statistics['sick'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Leave</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $statistics['leave'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Attendance Records</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Check In</th>
                                    <th class="px-4 py-2 text-left">Check Out</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">{{ $attendance->check_in ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $attendance->check_out ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <x-status-badge :status="$attendance->status" />
                                    </td>
                                    <td class="px-4 py-2">{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No records found</td>
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
