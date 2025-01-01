<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Make sure you have this __invoke method if you're using single-action controller
    public function __invoke(Request $request)
    {
        return view('admin.dashboard');
    }
    
    // Or if you're using regular controller methods, make sure they exist
    public function dashboard()
    {
        return view('admin.dashboard');
        // return response()->view('admin.dashboard');
    }

    public function list_user()
    {
        // Fetch all users from the database
        $users = User::all();

        // Pass users to the view
        return view('users.index', compact('users')); // Use the name of the blade file
    }
}