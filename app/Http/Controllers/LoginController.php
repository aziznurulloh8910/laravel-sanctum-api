<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{ 
    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $status = 401;
        $response = [
            'error' => 'Proses masuk gagal!. Silahkan coba kembali.', 
        ]; 
        
        if (Auth::attempt($credentials)) {
            $status = 200;
            $token = $request->user()->createToken('access_token')->plainTextToken; 
            $response = [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
        } 
        
        return response()->json($response, $status);

        $request->session()->regenerate();

        return back();
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->where('tokenable_id', Auth::user()->id)->delete();
        
        Auth::logout();  
        
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return back();
    }
}

