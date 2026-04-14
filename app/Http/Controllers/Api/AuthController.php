<?php

namespace App\Http\Controllers\api;

use App\Models\UserAccounts;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\Otp;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            $user = UserAccounts::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                    'message' => 'User not found'
                ], Response::HTTP_NOT_FOUND); // 404
            }
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'status' => 'INVALID_CREDENTIALS',
                    'message' => 'Invalid email or password'
                ], Response::HTTP_OK); // 200
            }
            if (!$user->email_verified_at) {

                return response()->json([
                    'email_verified' => false,
                    'email' => $user->email,
                    'success' => false,
                    'status' => 'NOT_VERIFIED',
                    'message' => 'Belum verifikasi email',
                ], Response::HTTP_OK); // 200
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'email_verified' => true,
                'success' => true,
                'status' => 'Success',
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], Response::HTTP_OK); // 200
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }


    public function loginWithGoogle(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'google_id' => 'required',
                'name' => 'required'
            ]);
            $user = UserAccounts::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'status' => 'NOT_FOUND',
                    'message' => 'User not found'
                ], Response::HTTP_OK); // 200
            }

            if (!$user->email_verified_at) {

                return response()->json([
                    'email_verified' => false,
                    'success' => false,
                    'status' => 'NOT_VERIFIED',
                    'message' => 'Belum verifikasi email',

                ], Response::HTTP_OK);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'email_verified' => true,
                'success' => true,
                'status' => 'Success',
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], Response::HTTP_OK); // 200
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function ceklogin(Request $request)
    {
        $email = $request->email;
        $user = UserAccounts::where('email', $email)->first();
        if (!$user) {
            return new AuthResource('error', 'Email Tidak Ditemukan', ['login' => false], 201);
        }

        return new AuthResource("success", 'Login Success', ['login' => true, 'email' => $user->email], 200);
    }
    public function registration(Request $request)
    {
        try {
            $validators = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:user_accounts',
                'phone' => 'required|string|max:15|unique:user_accounts',
                'password' => 'required|string|min:8',
            ], [
                'name.required' => 'Nama harus diisi',
                'email.required' => 'Email harus diisi',
                'email.unique' => 'Email sudah terdaftar',
                'phone.required' => 'Nomor telepon harus diisi',
                'phone.unique' => 'Nomor telepon sudah terdaftar',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'email.email' => 'Email tidak valid',
            ]);

            if ($validators->fails()) {
                return response()->json([
                    'status' => 'Gagal',
                    'message' => 'Validation Error',
                    'errors' => $validators->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY); // 422
            }

            $user = UserAccounts::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ], Response::HTTP_CREATED); // 201
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ]);

            $identifier = $request->otp_subject;

            $user = UserAccounts::where('email', $identifier)->first();

            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password berhasil dirubah', 'user' => $identifier], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => 'Gagal',
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // 500
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK); // 200
    }
}
