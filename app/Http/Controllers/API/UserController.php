<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function addUser(Request $request)
    {
        $data = $request->validate([
            'name' => ['required'],
            'phone' => ['required', 'digits:11', 'unique:users,phone'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6']
        ]);
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        $token = $user->createToken('token')->accessToken;

        return response()->json([
            'status' => true,
            'msg' => 'User account created',
            'token' => $token
        ]);
    }

    public function showUser($id)
    {
        $user = User::where('id', $id)->first();
        if(!$user){
            return response()->json([
                'status'=> false,
                'message' => 'No User Found'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Found',
            'user' => $user 
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $data = $request->validate([
            'name' => ['required'],
            'phone' => ['required', 'digits:11', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => ['required', 'confirmed', 'min:6']
        ]);
        $data['password'] =  bcrypt($request->password);

        $user = User::where('id', $id)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $user->update($data);
        return response()->json([
            'status' => true,
            'message' => 'User data updated',
            'user' => $user
        ]);

    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->first();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Account Deleted',
            'user' => $user
        ]);
    }
}
