@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <a href="{{ route('qr.index') }}" class="text-blue-500 hover:text-blue-700">&larr; Back to QR Codes</a>
                </div>

                <h2 class="text-2xl font-bold mb-6">QR Code: {{ $qrCode->code }}</h2>

                <!-- QR Code Display -->
                <div class="mb-8 p-6 border border-gray-300 rounded text-center">
                    <img src="{{ $qrCode->qr_data }}" alt="QR Code" class="inline-block" style="max-width: 300px;">
                    <p class="mt-4 text-gray-600">{{ $qrCode->code }}</p>
                </div>

                <!-- QR Code Details -->
                <div class="mb-8 grid grid-cols-2 gap-4">
                    <div class="border border-gray-300 rounded p-4">
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="text-lg font-semibold">
                            {{ $qrCode->isValid() ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                    <div class="border border-gray-300 rounded p-4">
                        <p class="text-gray-600 text-sm">Generated At</p>
                        <p class="text-lg font-semibold">{{ $qrCode->generated_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div class="border border-gray-300 rounded p-4">
                        <p class="text-gray-600 text-sm">Expires At</p>
                        <p class="text-lg font-semibold">
                            {{ $qrCode->expires_at ? $qrCode->expires_at->format('Y-m-d H:i') : 'No expiration' }}
                        </p>
                    </div>
                    <div class="border border-gray-300 rounded p-4">
                        <p class="text-gray-600 text-sm">Total Scans</p>
                        <p class="text-lg font-semibold">{{ $qrCode->attendances()->count() }}</p>
                    </div>
                </div>

                <!-- Recent Scans -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Recent Scans</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Check In</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Check Out</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scans as $scan)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $scan->user->name }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $scan->attendance_date->format('Y-m-d') }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $scan->check_in ?? '-' }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $scan->check_out ?? '-' }}</td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $scan->status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="border border-gray-300 px-4 py-2 text-center">No scans yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $scans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
