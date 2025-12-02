<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

class QrCodeController extends Controller
{
    /**
     * Display QR code generation page.
     */
    public function index()
    {
        if (auth()->user()->isAdmin() || auth()->user()->isManager()) {
            // Show all QR codes for admin/manager
            $qrCodes = QrCode::latest()->paginate(10);
        } else {
            // Show only user's own QR codes
            $qrCodes = auth()->user()->qrCodes()->latest()->paginate(10);
        }
        return view('qr.index', compact('qrCodes'));
    }

    /**
     * Generate new QR code.
     */
    public function generate(Request $request)
    {
        // Check if user is admin or manager
        if (!auth()->user()->isAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'expires_at' => 'nullable|date|after_or_equal:today'
        ]);

        // Get the employee
        $employee = \App\Models\User::findOrFail($validated['employee_id']);
        
        // Check if employee already has active QR code
        $existingQr = QrCode::where('user_id', $employee->id)
            ->where('is_active', true)
            ->first();

        if ($existingQr && !$existingQr->isExpired()) {
            return redirect()->back()->with('info', 'Employee sudah memiliki QR Code aktif');
        }

        $code = 'QR_' . $employee->id . '_' . uniqid();
        
        // Generate QR code image
        $qrCode = new EndroidQrCode($code);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Store QR code data
        $qrCodeModel = QrCode::create([
            'user_id' => $employee->id,  // QR untuk employee
            'code' => $code,
            'qr_data' => $result->getDataUri(),
            'generated_at' => now(),
            'expires_at' => $validated['expires_at'] ? \Carbon\Carbon::parse($validated['expires_at'])->endOfDay() : null,
            'is_active' => true
        ]);

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'qr_generate',
            'Generated QR code for ' . $employee->name,
            'QrCode',
            $qrCodeModel->id
        );

        return redirect()->back()->with('success', 'QR Code untuk ' . $employee->name . ' berhasil dibuat');
    }

    /**
     * Download QR code.
     */
    public function download(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);

        $qrCodeGenerator = new EndroidQrCode($qrCode->code);
        $writer = new PngWriter();
        $result = $writer->write($qrCodeGenerator);

        return response($result->getString(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr_' . $qrCode->code . '.png"');
    }

    /**
     * Deactivate QR code.
     */
    public function deactivate(QrCode $qrCode)
    {
        $this->authorize('update', $qrCode);

        $qrCode->update(['is_active' => false]);

        ActivityLog::log(
            auth()->id(),
            'qr_deactivate',
            'Deactivated QR code',
            'QrCode',
            $qrCode->id
        );

        return redirect()->back()->with('success', 'QR Code telah dinonaktifkan');
    }

    /**
     * Show QR code details.
     */
    public function show(QrCode $qrCode)
    {
        $this->authorize('view', $qrCode);
        
        $scans = $qrCode->attendances()->latest()->paginate(10);
        
        return view('qr.show', compact('qrCode', 'scans'));
    }
}
