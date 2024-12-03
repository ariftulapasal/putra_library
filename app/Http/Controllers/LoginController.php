<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;        // Import the User model
use Illuminate\Support\Facades\Auth; // Import the Auth facade
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'face_image' => 'required', // Ensure face image is provided
        ]);
    
        $faceData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->face_image));
        
        // Store user and face data
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'face_image' => $faceData,  // Store raw face data or integrate with an API here
        ]);
    
        Auth::login($user);
        return redirect()->intended('dashboard');
    }
}
