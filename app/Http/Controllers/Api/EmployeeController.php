<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FaceEmbedding;
use App\Models\QrToken;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return response()->json($user, 201);
    }

    public function registerFace(Request $request, $id)
    {
        $request->validate([
            'embedding' => 'required|array',
        ]);

        $user = User::findOrFail($id);

        DB::transaction(function() use ($user, $request) {
            FaceEmbedding::create([
                'user_id' => $user->id,
                'embedding' => $request->input('embedding'),
            ]);
        });

        return response()->json(['message' => 'Face embedding registered'], 201);
    }

    public function generateQr(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $deviceId = $request->input('device_id');

        $qr = QrToken::generateFor($user->id, $deviceId);

        return response()->json(['token' => $qr->token, 'expires_at' => $qr->expires_at], 201);
    }
}
