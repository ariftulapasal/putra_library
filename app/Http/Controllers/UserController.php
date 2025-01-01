<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    // public function list_user()
    // {
    //     // Fetch all users from the database
    //     $users = User::all();

    //     // Pass users to the view
    //     return view('users.index', compact('users')); // Use the name of the blade file
    // }

    public function dashboard()
    {
        return view('users.dashboard');
        // return response()->view('admin.dashboard');
    }
    public function index()
    {
        $users = User::all(); // Replace with your data fetching logic
        return view('users.index', compact('users'));
    }
}
