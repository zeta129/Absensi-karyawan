<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrToken;
use App\Models\FaceEmbedding;
use App\Models\FaceChallenge;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    protected function parseQrToken($token)
    {
        try {
            $decoded = base64_decode($token);
            [$empId, $timestamp, $rand, $hmac] = explode('|', $decoded);
            return [$empId, (int)$timestamp, $rand, $hmac];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function verifyQr(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string',
            'device_id' => 'required|string',
        ]);

        $qr = QrToken::where('token', $data['token'])->first();
        if (!$qr) {
            // Log failure
            return response()->json(['message' => 'Invalid token'], 400);
        }

        if ($qr->used) {
            return response()->json(['message' => 'Replay detected'], 400);
        }

        if ($qr->device_id && $qr->device_id !== $data['device_id']) {
            return response()->json(['message' => 'Device mismatch'], 400);
        }

        if ($qr->expires_at->isPast()) {
            return response()->json(['message' => 'Expired token'], 400);
        }

        // Mark used to prevent replay in a transaction
        DB::transaction(function() use ($qr) {
            $qr->used = true;
            $qr->save();
        });

        return response()->json(['employee_id' => $qr->employee_id], 200);
    }

    public function verifyFace(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|integer',
            'embedding' => 'required|array',
            'challenge' => 'required|string',
            'device_id' => 'required|string',
        ]);

        // Check challenge
        $challenge = FaceChallenge::where('challenge', $data['challenge'])
            ->where('expires_at', '>', now())
            ->where('completed', false)
            ->first();

        if (!$challenge) {
            return response()->json(['message' => 'Invalid or expired challenge'], 400);
        }

        // compare embeddings using Euclidean distance
        $registered = FaceEmbedding::where('user_id', $data['employee_id'])->get();
        if ($registered->isEmpty()) {
            return response()->json(['message' => 'No face registered for user'], 404);
        }

        $probe = $data['embedding'];
        $best = null;
        foreach ($registered as $r) {
            $dist = $this->euclideanDistance($probe, $r->embedding);
            if (is_null($best) || $dist < $best['dist']) {
                $best = ['dist' => $dist, 'id' => $r->id, 'user_id' => $r->user_id];
            }
        }

        if ($best['dist'] >= 0.6) {
            // Log failed attempt
            return response()->json(['message' => 'Low confidence', 'distance' => $best['dist']], 400);
        }

        // Mark challenge completed
        $challenge->completed = true;
        $challenge->save();

        return response()->json(['employee_id' => $best['user_id'], 'confidence' => 1 - $best['dist']], 200);
    }

    public function createChallenge(Request $request)
    {
        $user = $request->user();
        $challenge = FaceChallenge::createChallenge($user?->id);
        return response()->json(['challenge' => $challenge->challenge, 'expires_at' => $challenge->expires_at], 201);
    }

    public function checkIn(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|integer',
            'device_id' => 'required|string',
            'confidence' => 'required|numeric',
            'method' => 'required|string',
        ]);

        DB::transaction(function() use ($data) {
            Attendance::create([
                'employee_id' => $data['employee_id'],
                'timestamp' => now(),
                'method' => $data['method'],
                'confidence' => $data['confidence'],
                'device_id' => $data['device_id'],
                'anomaly_flag' => false,
            ]);
        });

        return response()->json(['message' => 'Checked in'], 201);
    }

    public function checkOut(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|integer',
            'device_id' => 'required|string',
        ]);

        DB::transaction(function() use ($data) {
            Attendance::create([
                'employee_id' => $data['employee_id'],
                'timestamp' => now(),
                'method' => 'manual',
                'confidence' => 1,
                'device_id' => $data['device_id'],
                'anomaly_flag' => false,
            ]);
        });

        return response()->json(['message' => 'Checked out'], 201);
    }

    private function euclideanDistance(array $a, array $b)
    {
        $sum = 0.0;
        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $d = ($a[$i] - $b[$i]);
            $sum += $d * $d;
        }
        return sqrt($sum);
    }
}
