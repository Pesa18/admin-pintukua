<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForgotPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $request->header('Authorization');

        if (!$auth || !str_starts_with($auth, 'Bearer ')) {
            return response()->json(['message' => 'OTP token required'], 401);
        }

        try {
            $token = str_replace('Bearer ', '', $auth);
            $payload = JWT::decode($token, new Key(config('app.key'), 'HS256'));

            if ($payload->scope !== 'reset_password') {
                return response()->json(['message' => 'Invalid token scope'], 403);
            }

            // inject ke request
            $request->merge([
                'otp_subject' => $payload->sub
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Invalid or expired OTP token'], 401);
        }

        return $next($request);
    }
}
