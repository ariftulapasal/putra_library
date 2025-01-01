<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->diffForHumans(),
                'last_login' => $user->last_login ? $user->last_login->diffForHumans() : null,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:4096', // Validate profile picture
        ]);

        // if ($request->hasFile('profile_photo')) {
        //     $photo = $request->file('profile_photo');
        //     $photo->storeAs('/storage/profile-photos/', $user->id . '.jpg');
        // }
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $photo->storePubliclyAs('profile-photos', $user->id . '.jpg', 'public'); 
        }

        if (isset($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect'
                ], 422);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // // Handle profile photo upload
        // if ($request->hasFile('profile_photo')) {
        //     // $path = $request->file('profile_photo')->storeAs(
        //     //     'public/profile-photos',
        //     //     $user->id . '.' . $request->file('profile_photo')->getClientOriginalExtension()
        //     // );
        //     $path = $request->file('profile_photo')->storeAs(
        //         'public/profile-photos', // Path relative to 'storage/app/public'
        //         $user->id . '.' . $request->file('profile_photo')->getClientOriginalExtension()
        //     );
        //     $user->profile_photo = str_replace('public/', '/storage/', $path);
        // }

        // if ($request->hasFile('profile_photo')) {
        //     $file = $request->file('profile_photo');

        //     $path = $file->storeAs(
        //         'public/profile-photos', // Ensure this is the correct folder
        //         $user->id . '.' . $file->getClientOriginalExtension()
        //     );

        //     $user->profile_photo = str_replace('public/', '/storage/', $path);
        //     $user->save();
        // } else {
        //     \Log::error('No file uploaded');
        //     return response()->json(['message' => 'No file uploaded'], 422);
        // }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}
