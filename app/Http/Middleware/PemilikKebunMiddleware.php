<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Kebun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PemilikKebunMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $kebun = Kebun::findOrFail($request->id);
        if($kebun->id_user != $user->user_id){
            return response()->json('Kebun not found', 404);
        }
        else{
            return $next($request);
        }
    }
}
