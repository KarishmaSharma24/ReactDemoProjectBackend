<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    use CustomResponseTrait;
    
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($user)->accessToken;
            return $this->successResponse($user, 'User login successfully');
        }
        return response(['error' => 'Unauthenticated'], 401);

    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        $input = $request->all();
        $input['password'] =  Hash::make($input['password']);
        $user = User::create($input);
        $user->token = $user->createToken($request->email)->accessToken;
        // $token = $user->createToken('MyApp')->accessToken;
        // return response(['token' => $token], 200);
        return $this->successResponse($user, 'User register successfully');    
    }    

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }
}
