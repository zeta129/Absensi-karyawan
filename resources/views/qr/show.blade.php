<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-white leading-tight">
                📱 QR Code: {{ $qrCode->code }}
            </h2>
            <a href="{{ route('qr.index') }}" class="btn-outline">← Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: QR Code Display -->
                <div class="lg:col-span-1">
                    <div class="card-modern animate-fade-in-up">
                        <div class="p-6 text-center">
                            <h3 class="text-lg font-semibold mb-4">QR Code Image</h3>
                            <img id="serverQrImage" src="{{ $qrCode->qr_data }}" alt="QR Code" class="w-full max-w-xs mx-auto border-2 border-purple-200 rounded">
                            <p class="mt-4 text-sm font-mono text-gray-600">{{ $qrCode->code }}</p>

                            <div class="mt-4 flex flex-col gap-2">
                                <button onclick="downloadServerQR()" class="btn-primary w-full">
                                    💾 Download
                                </button>
                                <button onclick="openScanModal()" class="btn-success w-full">
                                    📸 Scan Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Details & Scans -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="card-modern p-4">
                            <p class="text-xs text-gray-600 mb-1">Status</p>
                            <p class="text-lg font-bold">
                                @if($qrCode->isValid())
                                    <span class="badge-success">Active</span>
                                @else
                                    <span class="badge-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-modern p-4">
                            <p class="text-xs text-gray-600 mb-1">Total Scans</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $qrCode->attendances()->count() }}</p>
                        </div>
                        <div class="card-modern p-4">
                            <p class="text-xs text-gray-600 mb-1">Generated</p>
                            <p class="text-sm font-medium">{{ $qrCode->generated_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="card-modern p-4">
                            <p class="text-xs text-gray-600 mb-1">Expires</p>
                            <p class="text-sm font-medium">{{ $qrCode->expires_at ? $qrCode->expires_at->format('M d, Y') : '∞ Never' }}</p>
                        </div>
                    </div>

                    <!-- Recent Scans Table -->
                    <div class="card-modern animate-fade-in-up">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">📋 Recent Scans</h3>
                            <div class="overflow-x-auto">
                                <table class="table-modern">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm">Employee</th>
                                            <th class="px-4 py-2 text-left text-sm">Date</th>
                                            <th class="px-4 py-2 text-left text-sm">Check In</th>
                                            <th class="px-4 py-2 text-left text-sm">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($scans as $scan)
                                        <tr class="transition hover:bg-purple-50/40">
                                            <td class="px-4 py-2 font-medium">{{ $scan->user->name }}</td>
                                            <td class="px-4 py-2 text-sm">{{ $scan->attendance_date->format('M d, Y') }}</td>
                                            <td class="px-4 py-2 font-mono text-sm">{{ $scan->check_in ?? '-' }}</td>
                                            <td class="px-4 py-2">
                                                <span class="badge-success text-xs">{{ ucfirst($scan->status) }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-gray-500 text-sm">No scans yet</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($scans->count() > 0)
                            <div class="mt-4">
                                {{ $scans->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scan Modal -->
    <div id="scanModal" style="display: none;" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl">
            <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-pink-600 p-4 flex justify-between items-center rounded-t-lg">
                <h3 class="text-lg font-bold text-white">📸 Scan QR Code</h3>
                <button onclick="closeScanModal()" class="text-white text-2xl font-bold hover:opacity-75">×</button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Camera Feed -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">Camera</label>
                        <div class="relative bg-black rounded-lg overflow-hidden" style="aspect-ratio: 1;">
                            <video id="qrVideo" autoplay playsinline style="width:100%;height:100%;object-fit:cover;" class="bg-gray-900"></video>
                            <canvas id="qrCanvas" class="hidden"></canvas>
                            <div class="absolute inset-0 border-4 border-green-500 opacity-30 pointer-events-none"></div>
                        </div>
                        <p id="scanStatus" class="mt-2 text-sm text-gray-600 text-center">
                            📍 Align QR code within frame...
                        </p>
                    </div>

                    <!-- Scan Result -->
                    <div>
                        <label class="block text-sm font-semibold mb-2">Scan Result</label>
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4 min-h-32 border-2 border-dashed border-gray-300 transition-all duration-300" style="transition: all 0.3s ease;">
                                <p id="resultLabel" class="text-xs text-gray-600 mb-1">Waiting for QR code...</p>
                                <pre id="scannedValue" class="text-sm font-mono break-words whitespace-pre-wrap">-</pre>
                            </div>

                            <form id="scanForm" method="POST" action="{{ route('attendance.scan') }}" class="space-y-3">
                                @csrf
                                <input type="hidden" name="qr_code" id="scannedCode" value="">
                                <input type="hidden" name="type" value="checkin">

                                <button type="button" id="checkInBtn" disabled 
                                        onclick="submitScan()"
                                        class="btn-success w-full disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                    ✓ Check In
                                </button>

                                <button type="button" id="clearBtn" class="btn-outline w-full" onclick="clearScan()">
                                    🔄 Clear
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
            }
        }

        .animate-pulse {
            animation: pulse-glow 1s cubic-bezier(0.4, 0, 0.6, 1);
        }
    </style>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    let _videoStream = null;
    let _scanActive = false;
    let _lastDetectedCode = null;
    let _scanLoopId = null;
    let _audioContext = null;

    function initAudio() {
        if (!_audioContext) {
            _audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
    }

    function playBeep(frequency = 1000, duration = 200) {
        try {
            initAudio();
            const osc = _audioContext.createOscillator();
            const gain = _audioContext.createGain();
            osc.connect(gain);
            gain.connect(_audioContext.destination);
            osc.frequency.value = frequency;
            osc.type = 'sine';
            gain.gain.setValueAtTime(0.3, _audioContext.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, _audioContext.currentTime + duration / 1000);
            osc.start(_audioContext.currentTime);
            osc.stop(_audioContext.currentTime + duration / 1000);
        } catch (e) {
            console.log('Audio unavailable');
        }
    }

    function pulseAnimation() {
        const box = document.getElementById('scannedValue')?.parentElement;
        if (!box) return;
        box.classList.add('animate-pulse');
        box.style.background = '#dcfce7';
        box.style.borderColor = '#16a34a';
        setTimeout(() => {
            box.classList.remove('animate-pulse');
            box.style.boxShadow = '';
        }, 1200);
    }

    function openScanModal(){
        document.getElementById('scanModal').style.display = 'flex';
        setTimeout(startCameraScan, 100);
    }

    function closeScanModal(){
        document.getElementById('scanModal').style.display = 'none';
        stopCameraScan();
    }

    async function startCameraScan(){
        if (_scanActive) return;
        
        const video = document.getElementById('qrVideo');
        if (!video) {
            console.error('Video element not found');
            return;
        }

        try {
            _videoStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment',
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            });

            video.srcObject = _videoStream;
            _scanActive = true;

            // Use play() promise and wait for canplay event
            Promise.resolve(video.play()).catch(e => console.error('Play error:', e));
            
            video.addEventListener('canplay', scanFrame, { once: true });
            video.addEventListener('playing', scanFrame, { once: true });
            
        } catch (err) {
            const status = document.getElementById('scanStatus');
            if (status) {
                status.innerText = '❌ ' + (err.name === 'NotAllowedError' ? 'Camera permission denied' : err.message);
            }
        }
    }

    function stopCameraScan(){
        _scanActive = false;
        if (_scanLoopId) {
            cancelAnimationFrame(_scanLoopId);
            _scanLoopId = null;
        }
        if (_videoStream) {
            _videoStream.getTracks().forEach(t => t.stop());
            _videoStream = null;
        }
        const video = document.getElementById('qrVideo');
        if (video) video.srcObject = null;
    }

    function scanFrame(){
        if (!_scanActive) return;

        const video = document.getElementById('qrVideo');
        const canvas = document.getElementById('qrCanvas');

        if (!video || !canvas || video.videoWidth === 0 || video.videoHeight === 0) {
            _scanLoopId = requestAnimationFrame(scanFrame);
            return;
        }

        try {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d', { willReadFrequently: true });
            ctx.drawImage(video, 0, 0);

            const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imgData.data, imgData.width, imgData.height, { inversionAttempts: 'attemptBoth' });

            if (code?.data) {
                if (code.data !== _lastDetectedCode) {
                    _lastDetectedCode = code.data;
                    
                    playBeep(800, 150);
                    setTimeout(() => playBeep(1000, 150), 160);
                    
                    document.getElementById('scanStatus').innerText = '✓ QR code detected!';
                    document.getElementById('resultLabel').innerText = 'Scanned QR Code:';
                    document.getElementById('scannedValue').innerText = code.data;
                    document.getElementById('scannedCode').value = code.data;
                    document.getElementById('checkInBtn').disabled = false;
                    
                    pulseAnimation();
                    
                    if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                    stopCameraScan();
                    return;
                }
            } else {
                const status = document.getElementById('scanStatus');
                if (status?.innerText.includes('Scanning')) {
                    // Keep existing status
                } else {
                    if (status) status.innerText = '📍 Scanning... Align QR code';
                }
            }
        } catch (err) {
            console.error('Scan error:', err);
        }

        _scanLoopId = requestAnimationFrame(scanFrame);
    }

    function submitScan(){
        const code = document.getElementById('scannedCode')?.value;
        if (!code) {
            alert('No QR code scanned');
            return;
        }
        
        document.getElementById('checkInBtn').disabled = true;
        playBeep(1200, 100);
        document.getElementById('scanForm')?.submit();
    }

    function clearScan(){
        _lastDetectedCode = null;
        document.getElementById('scannedCode').value = '';
        document.getElementById('scannedValue').innerText = '-';
        document.getElementById('resultLabel').innerText = 'Waiting for QR code...';
        document.getElementById('checkInBtn').disabled = true;
        document.getElementById('scanStatus').innerText = '📍 Align QR code within frame...';
        
        const box = document.getElementById('scannedValue')?.parentElement;
        if (box) {
            box.style.background = '';
            box.style.borderColor = '';
            box.style.boxShadow = '';
            box.classList.remove('animate-pulse');
        }
        
        if (!_scanActive) {
            const video = document.getElementById('qrVideo');
            if (video?.srcObject) {
                _scanActive = true;
                scanFrame();
            }
        }
    }

    function downloadServerQR(){
        const img = document.getElementById('serverQrImage');
        if (!img?.src) return;
        const link = document.createElement('a');
        link.href = img.src;
        link.download = '{{ $qrCode->code }}.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush
