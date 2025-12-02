<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendance Reports') }}
            </h2>
            <a href="{{ route('reports.export', ['month_year' => $monthYear]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <!-- Filter Section -->
                    <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="month_year" class="block text-gray-700 text-sm font-bold mb-2">Month & Year</label>
                            <input type="month" id="month_year" name="month_year" value="{{ $monthYear }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div>
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User</label>
                            <select id="user_id" name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Present</p>
                        <p class="text-2xl font-bold text-green-600">{{ $summary['present'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Absent</p>
                        <p class="text-2xl font-bold text-red-600">{{ $summary['absent'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Late</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $summary['late'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Sick</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $summary['sick'] }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="text-gray-600 text-sm">Leave</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $summary['leave'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Records Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">User</th>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">Check In</th>
                                    <th class="px-4 py-2 text-left">Check Out</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('reports.user-detail', $attendance->user) }}" class="text-blue-500 hover:text-blue-700">
                                            {{ $attendance->user->name }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">{{ $attendance->check_in ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $attendance->check_out ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <x-status-badge :status="$attendance->status" />
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
