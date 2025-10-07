<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try{
            $validated = $request->safe()->all();
            $passwordHash = Hash::make($validated ['password']);
            $validated ['password'] = $passwordHash;
            $response = User::create($validated);

            if($response){
                return response() ->json([
                    'message' => 'Register Success',
                    'user' => $response
                ],201);
            }

        }catch (Exception $e){
            return response()->json([
                'message' => $e ->getMessage(),
                'data' => null
            ],500);
        }
    }

    public function login (LoginRequest $request)
    {
        try{
            $validated = $request->safe()->all();

            if(!Auth::attempt($validated)){
                return response()->json([
                    'message',
                    'data' => null ],401);
            }

            $user = $request->user();
            $token = $user->createToken('laravel_api',['*'])->plainTextToken;

            return response()->json([
                'message' => 'Login Success',
                'user' => $user,
                'token' => $token
            ],200);






        }catch (Exception $e){
            return response()->json([
                'message' => $e ->getMessage(),
                'data' => null
            ],500);
        }
    }

    public function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'messagge' => 'Logout Success',
                'data' => null
            ],200);

        }catch(Exception $e){
            return response()->json([
                'messagge' => $e->getMessage(),
                'data' => null
            ],500);

        }
    }
}
