<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('QR Code Management') }}
            </h2>
            <button onclick="showGenerateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Generate QR Code
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- QR Codes Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">Employee</th>
                                    <th class="px-4 py-2 text-left">Code</th>
                                    <th class="px-4 py-2 text-left">Generated At</th>
                                    <th class="px-4 py-2 text-left">Expires At</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($qrCodes as $qrCode)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $qrCode->user->name }}</td>
                                    <td class="px-4 py-2 font-mono text-sm">{{ $qrCode->code }}</td>
                                    <td class="px-4 py-2">{{ $qrCode->generated_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-2">
                                        {{ $qrCode->expires_at ? $qrCode->expires_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($qrCode->isValid())
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Active</span>
                                        @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center space-x-2">
                                        <a href="{{ route('qr.show', $qrCode) }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">View</a>
                                        <a href="{{ route('qr.download', $qrCode) }}" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">Download</a>
                                        @if($qrCode->is_active)
                                        <form action="{{ route('qr.deactivate', $qrCode) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm" onclick="return confirm('Deactivate?')">Deactivate</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-2 text-center text-gray-500">No QR codes found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $qrCodes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate QR Code Modal -->
    <div id="generateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Generate QR Code</h3>
            
            <form action="{{ route('qr.generate') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="employee_id" class="block text-gray-700 text-sm font-bold mb-2">Select Employee</label>
                    <select id="employee_id" name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach(\App\Models\User::where('role_id', 3)->get() as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->email }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="expires_at" class="block text-gray-700 text-sm font-bold mb-2">Expires At (Optional)</label>
                    <input type="date" id="expires_at" name="expires_at" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeGenerateModal()" class="px-4 py-2 text-gray-800 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showGenerateModal() {
        document.getElementById('generateModal').classList.remove('hidden');
    }

    function closeGenerateModal() {
        document.getElementById('generateModal').classList.add('hidden');
    }
    </script>
</x-app-layout>
