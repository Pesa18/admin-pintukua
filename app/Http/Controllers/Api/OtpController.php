<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Models\UserAccounts;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function requestOtp(Request $request)
    {
        try {
            $email = $request->email;
            $scope = $request->scope ?? 'verify_email';
            $user = UserAccounts::where('email', $email)->first();
            $jwtPayload = [
                'sub' => $email,
                'scope' => $scope,
                'iat' => time(),
                'exp' => time() + 3600
            ];
            $token_JWT = JWT::encode($jwtPayload, config('app.key'), 'HS256');
            if (!$user) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                    'message' => 'User not found',

                ], Response::HTTP_NOT_FOUND); // 404
            }
            Otp::updateOrCreate(
                ['user_id' => $user->uuid],
                [
                    'otp' => rand(1000, 9999),
                    'expires_at' => now()->addMinutes(5),
                    'is_used' => false,
                ]
            );

            $user->notify(new \App\Notifications\SendOtp($user->otps()->latest()->first()->otp));

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'OTP sent successfully',
                'token' => $token_JWT,
            ], Response::HTTP_OK); // 200     

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
    public function verifyEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required|string|min:4|max:4',
            ]);

            $email = $request->otp_subject;

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            $otp = Otp::whereHas('user', function ($query) use ($email) {
                $query->where('email', $email);
            })->where('otp', $request->otp)->where('is_used', false)->first();

            if (!$otp) {
                return response()->json(['success' => false, 'message' => 'OTP tidak valid'], 400);
            }

            if ($otp->isExpired()) {
                return response()->json(['success' => false, 'message' => 'OTP telah kedaluwarsa'], 400);
            }

            // Tandai sudah digunakan
            $otp->update(['is_used' => true]);

            UserAccounts::where('uuid', $otp->user_id)->update(['email_verified_at' => now()]);
            // Jika ingin buat token login
            // $user = User::firstOrCreate(['phone' => $otp->identifier]);
            // $token = $user->createToken('otp-login')->plainTextToken;
            $otp->delete(); // Hapus OTP setelah verifikasi berhasil

            return response()->json([
                'success' => true,
                'message' => 'OTP berhasil diverifikasi',
                // 'token' => $token ?? null,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'otp' => 'required|string|min:4|max:4'
            ]);
            $email = $request->otp_subject;
            $otp = Otp::whereHas('user', function ($query) use ($email) {
                $query->where('email', $email);
            })->where('otp', $request->otp)->where('is_used', false)->first();


            if (!$otp) {
                return response()->json(['message' => 'OTP salah'], 400);
            }

            if ($otp->is_used) {
                return response()->json(['message' => 'OTP sudah digunakan'], 400);
            }

            if ($otp->isExpired()) {
                return response()->json(['success' => false, 'message' => 'OTP telah kedaluwarsa'], 400);
            }
            $otp->delete();

            return response()->json([
                'success' => true,
                'message' => 'OTP valid',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }
}
