<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordLink;
use App\Mail\EmailVerificationLink;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'username' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
            'name' => 'required'
        ]);
        User::create([
            'role' => 1,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Akun berhasil dibuat'
        ]);
    }

    public function login(Request $request){
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Username atau Password salah.'
            ], 401);
        }
        $token = $user->createToken($request->username)->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => [
                'username' => $user->username,
                'email' => $user->email,
                'name' => $user->name
            ]
        ]);

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function aboutme(Request $request){
        $user = Auth::user();
        return new UserResource($user);
    }

    public function sendVerifLink(){
        $user = Auth::user();
        $url = URL::temporarySignedRoute('verif.email', now()->addMinute(5), ['email' => $user->email]);
        Mail::to($user->email)->send(new EmailVerificationLink($url));
        return response()->json([
            'message' => 'Lihat email-mu untuk verifikasi'
        ]);
    }

    public function verifEmail(Request $request){
        $cari = User::where('email', $request->email)->first();
        if(!$cari->email_verified_at){
            $cari->update([
                'email_verified_at' => now()
            ]);
        }
        return response()->json([
            'message' => 'Email ter-verifikasi.'
        ]);
    }

    public function sendresetLink(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'Akunmu tidak ditemukan'
            ], 404);
        } else{
            if(!$user->email_verified_at){
                return response()->json([
                    'message' => 'Email belum terverifikasi'
                ], 405);
            } else{
                $url = URL::temporarySignedRoute('reset.password', now()->addMinute(5), ['email' => $request->email]);
                Mail::to($request->email)->send(new ResetPasswordLink($url));
                return response()->json([
                    'message' => 'Lihat email-mu untuk reset password'
                ]);
            }
        }
    }

    public function resetPassword(Request $request){
        $request->validate([
            'password' => 'required'
        ]);
        $cari = User::where('email', $request->email)->first();
        $cari->update([
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'Password telah diubah'
        ]);
    }

}
