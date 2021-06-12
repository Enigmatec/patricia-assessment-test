<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function addUser(Request $request)
    {        
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }
        
        $data['password'] = bcrypt($data['password']);
       
        $user = User::create($data);
        $token = $user->createToken('token')->accessToken;

        return response()->json([
            'status' => true,
            'msg' => 'User account created',
            'token' => $token,
            'user' => new UserResource($user)
        ], 201);
    }

    public function showUser($id)
    {
        $user = User::where('id', $id)->first();
        if(! $user){
            return response()->json([
                'status'=> false,
                'message' => 'No User Found'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Found',
            'user' => new UserResource($user) 
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => ['required'],
            'phone' => ['required', 'string', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $data['password'] =  bcrypt($data['password']);

        $user = User::where('id', $id)->first();
        if(! $user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $user->update($data);
        return response()->json([
            'status' => true,
            'message' => 'User data updated',
            'user' => new UserResource($user)
        ], 201);

    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id)->first();
        if(! $user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User Account Deleted',
            'user' => new UserResource($user)
        ]);
    }
}
