<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            📋 {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <!-- Activity Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="table-modern">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">👤 User</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                    <th class="px-4 py-2 text-left">Description</th>
                                    <th class="px-4 py-2 text-left">IP Address</th>
                                    <th class="px-4 py-2 text-left">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="px-4 py-2">{{ $log->user->name }}</td>
                                    <td class="px-4 py-2">
                                        <span class="badge-info">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $log->description ?? '-' }}</td>
                                    <td class="px-4 py-2 font-mono text-sm text-gray-600">{{ $log->ip_address ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">No logs found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
