<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    // Support fields used by both the web views and the API
    protected $fillable = [
        'user_id', 'employee_id', 'qr_code_id',
        'attendance_date', 'timestamp', 'check_in', 'check_out',
        'status', 'notes', 'method', 'confidence', 'device_id', 'anomaly_flag'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
        'timestamp' => 'datetime',
        'confidence' => 'float',
        'anomaly_flag' => 'boolean',
    ];

    /**
     * Get the user for this attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR code used for this attendance.
     */
    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    /**
     * Get duration of work in hours.
     */
    public function getWorkDurationAttribute(): ?string
    {
        if ($this->check_in && $this->check_out) {
            $start = \Carbon\Carbon::parse($this->check_in);
            $end = \Carbon\Carbon::parse($this->check_out);
            return ($start->diffInMinutes($end) / 60) . ' hours';
        }

        // fallback: if timestamp exists but no check_in/out
        return null;
    }

    /**
     * Check if user is late.
     */
    public function isLate(): bool
    {
        if (!$this->check_in) return false;
        // If check_in is a datetime, compare time portion
        $time = is_string($this->check_in) ? $this->check_in : (string)($this->check_in->format('H:i:s'));
        return $time > '08:00:00';
    }
}
