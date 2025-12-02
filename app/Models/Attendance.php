<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'qr_code_id',
        'attendance_date',
        'check_in',
        'check_out',
        'status',
        'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s'
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
            return $start->diffInMinutes($end) / 60 . ' hours';
        }
        return null;
    }

    /**
     * Check if user is late.
     */
    public function isLate(): bool
    {
        if (!$this->check_in) return false;
        // Assume work starts at 08:00
        return $this->check_in > '08:00:00';
    }
}
