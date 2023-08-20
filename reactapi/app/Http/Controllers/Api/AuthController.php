<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function signup(SignupRequest $request)
    {
       $data=$request->validated();
        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => Hash::make($data['password']),
        ]);
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }
    function login(LoginRequest $req)
    {
        $credentials = $req->validated();
        if (!Auth::attempt($credentials)) {
            return response(["message"=>"email ou senha incorrecta"],422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }
    function logout(Request $req)
    {
        $user = $req->user();
        $user->currentAccessToken()->delete();
        return response(["message"=>"logout"], 204);
    }
}
