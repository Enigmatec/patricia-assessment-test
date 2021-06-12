<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
 
    public function loginUser(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if(!Auth::attempt($credential)){
            return response()->json([
                'status' => false,
                'message' => 'lncorrect email or password'
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->accessToken;

        return response()->json([
            'status' => true,
            'msg' => 'Login Sucessful',
            'token' => $token,
            'user' => $user 
        ]);

    }

}
