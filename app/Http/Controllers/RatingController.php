<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function unrated(){
        $user = Auth::user();
        $data = TransaksiItem::where('id_user', $user->user_id)->where('isRated', false);
        return TransaksiItem::collection($data);
    }
}
