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
            'jenis_kelamin' => 'required',
            'name' => 'required',
            'telp' => 'required'
        ]);
        User::create([
            'role' => 1,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'jenis_kelamin' => $request->jenis_kelamin,
            'telp' => $request->telp,
            'gambar' => null
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
                'role' => $user->role,
                'email' => $user->email,
                'name' => $user->name,
                'telp' => $user->telp,
                'jenis_kelamin' => $user->jenis_kelamin,
                'gambar' => 'http://127.0.0.1:8000/storage/' . $user->gambar
            ]
        ]);

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }

    public function aboutme(Request $request){
        $user = Auth::user();
        return response()->json(new UserResource($user));
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

    public function updatePhoto(Request $request){
        $user = Auth::user();
        if($request->hasFile('gambar')){
            $file = $request->file('gambar');
            $fileName = $this->quickRandom().'.'.$file->extension();
            $path = $file->storeAs('foto_profile', $fileName, 'public');
            $user->update([
                'gambar' => $path
            ]);
            return response()->json([
                'message' => 'Foto profile berhasil di-update'
            ]);
        }else{
            return response()->json([
                'message' => 'Foto profile gagal dii-update'
            ], 500);
        }

    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }

}
