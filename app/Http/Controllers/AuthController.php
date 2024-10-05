<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = Validator::make($request->all(), [
            'username' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',

        ]);

        if($data->fails()){
            return response()->json('Formmu belum sesuai', 406);
        }
        else{
            User::create([
                'role' => 1,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'firstname' => $request->firstname,
                'lastname' => $request->lastname
            ]);
            return response()->json('Akun telah dibuat',200);

        }
    }

    public function login(Request $request){
        $data = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($data->fails()){
            return response()->json('Formmu belum sesuai', 406);
        }
        else{
            $user = User::where('username', $request->username)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => ['Formnya belum sesuai lee.'],
                ]);
            }

            return $user->createToken($request->username)->plainTextToken;
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json('Kamu berhasil logout');
    }

    public function aboutme(Request $request){
        $user = Auth::user();
        return new UserResource($user);
    }
}
