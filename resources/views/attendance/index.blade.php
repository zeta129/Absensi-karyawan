<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            ✅ {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-modern animate-fade-in-up">
                <div class="p-6">
                    @if(session('success'))
                    <div class="alert-modern mb-4" style="--alert-type: success;">
                        ✅ {{ session('success') }}
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                        {{ session('info') }}
                    </div>
                    @endif

                    <!-- QR Scanner Section (for employees) -->
                    @if(auth()->user()->isEmployee())
                    <div class="mb-8 p-6 border border-blue-300 rounded bg-blue-50">
                        <h3 class="text-lg font-bold mb-4">Attendance Scan</h3>
                        <div class="space-y-4">
                            <!-- Tabs for Camera/Manual -->
                            <div class="flex gap-2 mb-4">
                                <button type="button" id="employee-camera-tab" onclick="switchEmployeeTab('camera')" 
                                        class="px-4 py-2 bg-blue-500 text-white rounded font-semibold">
                                    📷 Camera
                                </button>
                                <button type="button" id="employee-manual-tab" onclick="switchEmployeeTab('manual')" 
                                        class="px-4 py-2 bg-gray-400 text-white rounded font-semibold">
                                    ⌨️ Manual
                                </button>
                            </div>

                            <!-- Camera Scanner -->
                            <div id="employee-camera-section" class="space-y-3">
                                <div class="border-2 border-dashed border-gray-400 rounded p-4 bg-white">
                                    <video id="employee-camera" width="100%" height="400" class="rounded bg-black"></video>
                                </div>
                                <p id="employee-scan-status" class="text-sm text-gray-600 text-center">Arahkan kamera ke QR code...</p>
                            </div>

                            <!-- Manual Input -->
                            <div id="employee-manual-section" class="hidden space-y-3">
                                <form id="qr-scan-form" action="{{ route('attendance.scan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" id="scan-type" value="checkin">
                                    <input type="text" name="qr_code" placeholder="Paste QR code here..." 
                                           class="w-full px-3 py-2 border border-gray-300 rounded" autofocus>
                                </form>
                            </div>

                            <!-- Type Selection -->
                            <div class="flex gap-3">
                                <button type="button" id="employee-checkin-btn" onclick="setEmployeeScanType('checkin')" 
                                        class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded font-semibold">
                                    Check In
                                </button>
                                <button type="button" id="employee-checkout-btn" onclick="setEmployeeScanType('checkout')" 
                                        class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded font-semibold">
                                    Check Out
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin/Manager Scanner Section -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="mb-8 p-6 border border-purple-300 rounded bg-purple-50">
                        <h3 class="text-lg font-bold mb-4">Employee Scanner</h3>
                        <div class="space-y-4">
                            <!-- Tabs for Camera/Manual -->
                            <div class="flex gap-2 mb-4">
                                <button type="button" id="admin-camera-tab" onclick="switchAdminTab('camera')" 
                                        class="px-4 py-2 bg-purple-500 text-white rounded font-semibold">
                                    📷 Camera
                                </button>
                                <button type="button" id="admin-manual-tab" onclick="switchAdminTab('manual')" 
                                        class="px-4 py-2 bg-gray-400 text-white rounded font-semibold">
                                    ⌨️ Manual
                                </button>
                            </div>

                            <!-- Camera Scanner -->
                            <div id="admin-camera-section" class="space-y-3">
                                <div class="border-2 border-dashed border-gray-400 rounded p-4 bg-white">
                                    <video id="admin-camera" width="100%" height="400" class="rounded bg-black"></video>
                                </div>
                                <p id="admin-scan-status" class="text-sm text-gray-600 text-center">Arahkan kamera ke QR code employee...</p>
                            </div>

                            <!-- Manual Input -->
                            <div id="admin-manual-section" class="hidden space-y-3">
                                <form id="admin-scan-form" action="{{ route('attendance.admin-scan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" id="admin-scan-type" value="checkin">
                                    <input type="text" name="qr_code" placeholder="Paste employee QR code here..." 
                                           class="w-full px-3 py-2 border border-gray-300 rounded" autofocus>
                                </form>
                            </div>

                            <!-- Type Selection -->
                            <div class="flex gap-3">
                                <button type="button" id="admin-checkin-btn" onclick="setAdminScanType('checkin')" 
                                        class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded font-semibold">
                                    Check In
                                </button>
                                <button type="button" id="admin-checkout-btn" onclick="setAdminScanType('checkout')" 
                                        class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded font-semibold">
                                    Check Out
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                <!-- Attendance Records -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Check In</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Check Out</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Notes</th>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->user->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_out ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @php
                                        $statusClasses = [
                                            'present' => 'bg-green-100 text-green-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            'late' => 'bg-yellow-100 text-yellow-800',
                                            'sick' => 'bg-orange-100 text-orange-800',
                                            'leave' => 'bg-purple-100 text-purple-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-sm font-semibold {{ $statusClasses[$attendance->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $attendance->notes ?? '-' }}</td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm" 
                                                onclick="return confirm('Delete?')">Delete</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">No attendance records found</td>
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

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
let employeeVideoStream = null;
let adminVideoStream = null;
let employeeScanningActive = false;
let adminScanningActive = false;

// ====== EMPLOYEE SCANNING ======
function switchEmployeeTab(tab) {
    const cameraSection = document.getElementById('employee-camera-section');
    const manualSection = document.getElementById('employee-manual-section');
    const cameraTab = document.getElementById('employee-camera-tab');
    const manualTab = document.getElementById('employee-manual-tab');
    
    if (tab === 'camera') {
        cameraSection.classList.remove('hidden');
        manualSection.classList.add('hidden');
        cameraTab.classList.add('ring-4', 'ring-yellow-300');
        manualTab.classList.remove('ring-4', 'ring-yellow-300');
        startEmployeeCamera();
    } else {
        cameraSection.classList.add('hidden');
        manualSection.classList.remove('hidden');
        cameraTab.classList.remove('ring-4', 'ring-yellow-300');
        manualTab.classList.add('ring-4', 'ring-yellow-300');
        stopEmployeeCamera();
        document.querySelector('#qr-scan-form input[name="qr_code"]').focus();
    }
}

function startEmployeeCamera() {
    if (employeeScanningActive) return;
    
    employeeScanningActive = true;
    const video = document.getElementById('employee-camera');
    const statusElement = document.getElementById('employee-scan-status');
    
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            employeeVideoStream = stream;
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            
            scanEmployeeQRCode();
        })
        .catch(err => {
            statusElement.textContent = '❌ Camera tidak tersedia: ' + err.message;
            employeeScanningActive = false;
        });
}

function stopEmployeeCamera() {
    employeeScanningActive = false;
    if (employeeVideoStream) {
        employeeVideoStream.getTracks().forEach(track => track.stop());
        employeeVideoStream = null;
    }
}

function scanEmployeeQRCode() {
    if (!employeeScanningActive) return;
    
    const video = document.getElementById('employee-camera');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);
    
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 2 });
    
    if (code) {
        document.getElementById('employee-manual-section').querySelector('input[name="qr_code"]').value = code.data;
        document.getElementById('qr-scan-form').submit();
    } else {
        requestAnimationFrame(scanEmployeeQRCode);
    }
}

function setEmployeeScanType(type) {
    document.getElementById('scan-type').value = type;
    document.getElementById('employee-checkin-btn').classList.remove('ring-4', 'ring-yellow-300');
    document.getElementById('employee-checkout-btn').classList.remove('ring-4', 'ring-yellow-300');
    
    if (type === 'checkin') {
        document.getElementById('employee-checkin-btn').classList.add('ring-4', 'ring-yellow-300');
    } else {
        document.getElementById('employee-checkout-btn').classList.add('ring-4', 'ring-yellow-300');
    }
}

// ====== ADMIN/MANAGER SCANNING ======
function switchAdminTab(tab) {
    const cameraSection = document.getElementById('admin-camera-section');
    const manualSection = document.getElementById('admin-manual-section');
    const cameraTab = document.getElementById('admin-camera-tab');
    const manualTab = document.getElementById('admin-manual-tab');
    
    if (tab === 'camera') {
        cameraSection.classList.remove('hidden');
        manualSection.classList.add('hidden');
        cameraTab.classList.add('ring-4', 'ring-yellow-300');
        manualTab.classList.remove('ring-4', 'ring-yellow-300');
        startAdminCamera();
    } else {
        cameraSection.classList.add('hidden');
        manualSection.classList.remove('hidden');
        cameraTab.classList.remove('ring-4', 'ring-yellow-300');
        manualTab.classList.add('ring-4', 'ring-yellow-300');
        stopAdminCamera();
        document.querySelector('#admin-scan-form input[name="qr_code"]').focus();
    }
}

function startAdminCamera() {
    if (adminScanningActive) return;
    
    adminScanningActive = true;
    const video = document.getElementById('admin-camera');
    const statusElement = document.getElementById('admin-scan-status');
    
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(stream => {
            adminVideoStream = stream;
            video.srcObject = stream;
            video.setAttribute('playsinline', true);
            video.play();
            
            scanAdminQRCode();
        })
        .catch(err => {
            statusElement.textContent = '❌ Camera tidak tersedia: ' + err.message;
            adminScanningActive = false;
        });
}

function stopAdminCamera() {
    adminScanningActive = false;
    if (adminVideoStream) {
        adminVideoStream.getTracks().forEach(track => track.stop());
        adminVideoStream = null;
    }
}

function scanAdminQRCode() {
    if (!adminScanningActive) return;
    
    const video = document.getElementById('admin-camera');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);
    
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 2 });
    
    if (code) {
        document.getElementById('admin-manual-section').querySelector('input[name="qr_code"]').value = code.data;
        document.getElementById('admin-scan-form').submit();
    } else {
        requestAnimationFrame(scanAdminQRCode);
    }
}

function setAdminScanType(type) {
    document.getElementById('admin-scan-type').value = type;
    document.getElementById('admin-checkin-btn').classList.remove('ring-4', 'ring-yellow-300');
    document.getElementById('admin-checkout-btn').classList.remove('ring-4', 'ring-yellow-300');
    
    if (type === 'checkin') {
        document.getElementById('admin-checkin-btn').classList.add('ring-4', 'ring-yellow-300');
    } else {
        document.getElementById('admin-checkout-btn').classList.add('ring-4', 'ring-yellow-300');
    }
}

// Initialize: Default to manual input on page load
document.addEventListener('DOMContentLoaded', function() {
    // Employee init
    if (document.getElementById('employee-manual-tab')) {
        document.getElementById('employee-manual-tab').classList.add('ring-4', 'ring-yellow-300');
        setEmployeeScanType('checkin');
    }
    
    // Admin init
    if (document.getElementById('admin-manual-tab')) {
        document.getElementById('admin-manual-tab').classList.add('ring-4', 'ring-yellow-300');
        setAdminScanType('checkin');
    }
});

// Stop cameras when page is unloaded
window.addEventListener('beforeunload', function() {
    stopEmployeeCamera();
    stopAdminCamera();
});
</script>
</x-app-layout>
