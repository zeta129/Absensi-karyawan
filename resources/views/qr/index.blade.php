<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-white leading-tight">
                📱 {{ __('QR Code Management') }}
            </h2>
            <div class="hidden sm:block">
                <button onclick="showGenerateModal()" class="btn-primary">
                    ✨ Generate QR Code
                </button>
            </div>
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
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="table-modern">
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
                                <tr class="transition hover:bg-purple-50/40">
                                    <td class="px-4 py-3">{{ $qrCode->user->name }}</td>
                                    <td class="px-4 py-3 font-mono text-sm">{{ $qrCode->code }}</td>
                                    <td class="px-4 py-3">{{ $qrCode->generated_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">{{ $qrCode->expires_at ? $qrCode->expires_at->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($qrCode->isValid())
                                            <span class="badge-success">Active</span>
                                        @else
                                            <span class="badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('qr.show', $qrCode) }}" class="btn-primary" style="display:inline-block;padding:0.35rem 0.6rem;margin-right:6px;">View</a>
                                        <a href="{{ route('qr.download', $qrCode) }}" class="btn-success" style="display:inline-block;padding:0.35rem 0.6rem;margin-right:6px;">Download</a>
                                        @if($qrCode->is_active)
                                            <form action="{{ route('qr.deactivate', $qrCode) }}" method="POST" class="inline" style="display:inline-block">
                                                @csrf
                                                <button type="submit" class="btn-danger" style="padding:0.35rem 0.6rem;">Deactivate</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">No QR codes found</td>
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
                        <button type="button" class="px-4 py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700" onclick="previewClientQR()">Preview QR (client)</button>
                        <button type="button" id="downloadPreviewBtn" class="px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700 hidden" onclick="downloadPreviewQR()">Download Preview</button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                            Generate (server)
                        </button>
                </div>
            </form>
            
                <div id="qrPreviewContainer" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview (client-side). This will not create a DB record until you click "Generate (server)".</p>
                    <div id="qrPreview" class="flex justify-center"></div>
                </div>
        </div>
    </div>

    <script>
    function showGenerateModal() {
        document.getElementById('generateModal').classList.remove('hidden');
    }

    function closeGenerateModal() {
        document.getElementById('generateModal').classList.add('hidden');
            clearPreviewQR();
    }

        // Client-side QR preview using qrcode.js
        let _previewQr = null;
        function previewClientQR() {
            const employeeId = document.getElementById('employee_id').value;
            if (!employeeId) {
                alert('Please select an employee first');
                return;
            }
            const expires = document.getElementById('expires_at').value || '';
            // Create a simple payload — server uses its own format; preview is for convenience
            const payload = `QR_EMP_${employeeId}_${Date.now()}${expires?('_' + expires):''}`;

            const container = document.getElementById('qrPreview');
            container.innerHTML = '';
            // qrcode.js expects a DOM element
            try {
                if (_previewQr) { _previewQr.clear(); }
            } catch(e) {}
            _previewQr = new QRCode(container, {
                text: payload,
                width: 220,
                height: 220,
                colorDark : '#000000',
                colorLight : '#ffffff',
                correctLevel : QRCode.CorrectLevel.H
            });
            document.getElementById('qrPreviewContainer').classList.remove('hidden');
            document.getElementById('downloadPreviewBtn').classList.remove('hidden');
        }

        function clearPreviewQR(){
            const container = document.getElementById('qrPreview');
            if (container) container.innerHTML = '';
            document.getElementById('qrPreviewContainer').classList.add('hidden');
            document.getElementById('downloadPreviewBtn').classList.add('hidden');
            _previewQr = null;
        }

        function downloadPreviewQR(){
            const container = document.getElementById('qrPreview');
            const img = container.querySelector('img') || container.querySelector('canvas');
            if (!img) { alert('No preview available'); return; }
            let dataUrl = '';
            if (img.tagName.toLowerCase() === 'img') dataUrl = img.src;
            else dataUrl = img.toDataURL('image/png');

            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = 'qr_preview.png';
            document.body.appendChild(a);
            a.click();
            a.remove();
        }
    </script>

        <!-- qrcode.js (client-side QR generator) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <!-- Floating CTA for small screens / always-visible -->
        <style>
        #floatingGenerate {
            position: fixed;
            right: 24px;
            bottom: 24px;
            z-index: 60;
            border-radius: 9999px;
            padding: 12px 18px;
            background: linear-gradient(90deg,#6d28d9,#ec4899);
            color: #fff;
            box-shadow: 0 10px 30px rgba(99,102,241,0.12);
            display:flex;align-items:center;gap:8px;
        }
        #floatingGenerate:hover{ transform: translateY(-4px); }
        </style>

        <button id="floatingGenerate" onclick="showGenerateModal()" title="Generate QR Code">
            ✨ Generate QR
        </button>
</x-app-layout>
