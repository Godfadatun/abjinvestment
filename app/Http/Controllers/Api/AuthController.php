<?php

namespace App\Http\Controllers\Api;

use App\User;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name'=>'required|max:55',
        //     'email'=>'email|required',
        //     'phone'=>'required|max:11',
        //     'password'=>'required|confirmed'
        // ]);

        $input = $request->all();
        $input['password'] = Hash::make($request->password);

        $user = User::create($input);

        $accessToken = $user->createToken('authToken')->accessToken ;

        return response([
            'message'=> 'Valid Credentials',
            'status' => 'Authorized',
            'user'=>$user,
            'access_token'=> $accessToken
            ]);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        
        if (!auth()->attempt($loginData)) {
            return response([
                'message'=> 'Invalid Credentials',
                'status' => 'Unauthorized'
                ]);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
            'message'=> 'Logged In',
            'status' => 'Authorized',
            'user'=> auth()->user(),
            'access_token'=> $accessToken
            ]);
    }
}
