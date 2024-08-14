<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function __construct()
    {
        // Hanya terapkan middleware 'auth:api' pada rute yang memerlukan autentikasi
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    // Metode untuk registrasi pengguna
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600 // Waktu kedaluwarsa token dalam detik
        ], 201);
    }

    // Metode untuk login pengguna
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $token = JWTAuth::fromUser($user);

            if (!$token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 3600 // Waktu kedaluwarsa token dalam detik
            ]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }




    // Metode untuk logout
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }

    // public function index()
    // {
    //     return response()->json([
    //         'message' => 'Welcome to the AuthController',
    //         'available_endpoints' => [
    //             'POST /login' => 'Authenticate user and get a token',
    //             'POST /register' => 'Register a new user',
    //             'POST /logout' => 'Logout the user'
    //         ]
    //     ]);
    // }
}
