<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FaceChallenge extends Model
{
    protected $table = 'face_challenges';
    protected $guarded = [];
    protected $dates = ['expires_at'];

    public static function createChallenge($userId = null, $ttlSeconds = 5)
    {
        $challenge = Str::random(8);
        return self::create([
            'user_id' => $userId,
            'challenge' => $challenge,
            'expires_at' => now()->addSeconds($ttlSeconds),
            'completed' => false,
        ]);
    }
}
