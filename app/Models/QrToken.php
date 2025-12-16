<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QrToken extends Model
{
    protected $table = 'qr_tokens';
    protected $guarded = [];
    protected $dates = ['expires_at'];

    public static function generateFor($employeeId, $deviceId = null, $ttlSeconds = 30)
    {
        $payload = $employeeId . '|' . now()->timestamp . '|' . Str::random(32);
        $hmac = hash_hmac('sha256', $payload, config('app.key'));
        $token = base64_encode($payload . '|' . $hmac);

        return self::create([
            'token' => $token,
            'employee_id' => $employeeId,
            'device_id' => $deviceId,
            'expires_at' => now()->addSeconds($ttlSeconds),
            'used' => false,
        ]);
    }
}
