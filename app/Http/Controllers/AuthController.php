<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(Request $request)
    {
        // Validasi kredensial
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek validasi
        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan pesan kesalahan tanpa respons JSON
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cek apakah kredensial valid
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Buat token dengan nama yang spesifik
            $user = Auth::user();
            $token = $user->createToken('Triasmitra2024IT')->plainTextToken;

            // Tampilkan pesan sukses dan redirect ke dashboard
            Alert::success('Success', 'Login success!');
            return redirect()->intended('/dashboard');
        } else {
            // Jika kredensial tidak valid, kembalikan pesan kesalahan tanpa respons JSON
            Alert::error('Error', 'Username atau password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function login(Request $request)
    {
        // Validasi kredensial
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek apakah kredensial valid
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Buat token dengan nama yang spesifik
            $token = $user->createToken('Triasmitra2024IT')->accessToken;

            // Kembalikan token sebagai respons JSON
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => 3600 // Waktu kedaluwarsa token dalam detik
            ]);
        } else {
            // Jika kredensial tidak valid, kembalikan respons error
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password'
        ]);

        $validated['password'] = Hash::make($request['password']);

        $user = User::create($validated);

        Alert::success('Success', 'Register user has been successfully !');
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
        Alert::success('Success', 'Log out success !');
        return redirect('/login');
    }
}
