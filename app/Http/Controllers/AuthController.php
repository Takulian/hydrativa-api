<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordLink;
use App\Mail\EmailVerificationLink;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
            // 'jenis_kelamin' => 'required',
            'name' => 'required',
            // 'telp' => 'required'
        ]);
        User::create([
            'role' => 2,
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

    public function login(Request $request)
    {
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
                'gambar' => $user->gambar ? url('/storage/' . $user->gambar) : null
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }

    public function aboutme(Request $request)
    {
        $user = Auth::user();
        return response()->json(new UserResource($user));
    }

    public function sendVerifLink()
    {
        $user = Auth::user();        

        // Membuat URL sementara dengan tanda tangan
        $url = URL::temporarySignedRoute('verif.email', now()->addMinute(5), ['email' => $user->email]);

        // Parsing URL untuk mendapatkan query parameters
        $parsedUrl = parse_url($url); // Parse URL
        parse_str($parsedUrl['query'], $queryParams); // Parse query string

        // Mendapatkan parameter dari query string
        $email = $queryParams['email'] ?? null;
        $expires = $queryParams['expires'] ?? null;
        $signature = $queryParams['signature'] ?? null;

        // Encode semua parameter
        $encodedEmail = urlencode($email);
        $encodedExpires = urlencode($expires);
        $encodedSignature = urlencode($signature);

        // Buat URL frontend untuk dikirim melalui email
        $url_frontend = env('FRONTEND_URL') . '/verified?email=' . $encodedEmail . '&expires=' . $encodedExpires . '&signature=' . $encodedSignature;

        // Kirimkan email dengan URL reset
        Mail::to($user->email)->send(new EmailVerificationLink($url_frontend, $user->name));

        return response()->json([
            'message' => 'Lihat email-mu untuk verifikasi'
        ]);
    }

    public function verifEmail(Request $request)
    {
        $cari = User::where('email', $request->email)->first();
        if (!$cari->email_verified_at) {
            $cari->update([
                'email_verified_at' => now()
            ]);
        }
        return response()->json([
            'message' => 'Email ter-verifikasi.'
        ]);
    }

    public function sendresetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Akunmu tidak ditemukan'
            ], 404);
        } else {
            if (!$user->email_verified_at) {
                return response()->json([
                    'message' => 'Email belum terverifikasi'
                ], 405);
            } else {
                // Membuat URL sementara dengan tanda tangan
                $url = URL::temporarySignedRoute('reset.password', now()->addMinutes(5), ['email' => $request->email]);

                // Parsing URL untuk mendapatkan query parameters
                $parsedUrl = parse_url($url); // Parse URL
                parse_str($parsedUrl['query'], $queryParams); // Parse query string

                // Mendapatkan parameter dari query string
                $email = $queryParams['email'] ?? null;
                $expires = $queryParams['expires'] ?? null;
                $signature = $queryParams['signature'] ?? null;

                // Encode semua parameter
                $encodedEmail = urlencode($email);
                $encodedExpires = urlencode($expires);
                $encodedSignature = urlencode($signature);

                // Buat URL frontend untuk dikirim melalui email
                $url_frontend = env('FRONTEND_URL') . '/reset-password?email=' . $encodedEmail . '&expires=' . $encodedExpires . '&signature=' . $encodedSignature;

                // Kirimkan email dengan URL reset
                Mail::to($request->email)->send(new ResetPasswordLink($url_frontend, $user->name));
                // Mail::to($request->email)->send(new ResetPasswordLink($url));

                return response()->json([
                    'message' => 'Lihat email-mu untuk reset password',                    
                ]);
            }
        }
    }

    public function resetPassword(Request $request)
    {
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

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('gambar')) {
            $pathLama = storage_path('app/public/' . $user->gambar);
            if (File::exists($pathLama)) {
                File::delete($pathLama);
            }
            $file = $request->file('gambar');
            $fileName = $this->quickRandom() . '.' . $file->extension();
            $path = $file->storeAs('foto_profile', $fileName, 'public');
            $user->update([
                'gambar' => $path
            ]);
            return response()->json([
                'message' => 'Foto profile berhasil di-update'
            ]);
        } else {
            return response()->json([
                'message' => 'Foto profile gagal dii-update'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request_data = $request->validate([
            'username' => 'required',
            'jenis_kelamin' => 'required',
            'name' => 'required',
            'telp' => 'required'
        ]);
        $user->update($request_data);
        return response()->json([
            'message' => 'Data profile telah diupdate'
        ]);
    }

    public function updateMobile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'username' => 'required',
            'jenis_kelamin' => 'required',
            'name' => 'required',
            'telp' => 'required'
        ]);
        if ($request->hasFile('gambar')) {
            $pathLama = storage_path('app/public/' . $user->gambar);
            if (File::exists($pathLama)) {
                File::delete($pathLama);
            }
            $file = $request->file('gambar');
            $fileName = $this->quickRandom() . '.' . $file->extension();
            $path = $file->storeAs('foto_profile', $fileName, 'public');
            $user->update([
                'gambar' => $path
            ]);
        }
        $user->update([
            'username' => $request->username,
            'jenis_kelamin' => $request->jenis_kelamin,
            'name' => $request->name,
            'telp' => $request->telp
        ]);
        return response()->json([
            'message' => 'Data profile telah diupdate'
        ]);
    }

    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }
}
