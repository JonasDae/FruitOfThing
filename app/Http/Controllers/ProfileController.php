<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    //prevent non authorized access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //get profile
        $profile = Auth::user();
        $users = User::get();

        return view('profiles.index', compact('profile', 'users'));
    }

    public function edit(User $user)
    {
        dd($user);
        return view('profiles.edit', compact('user'));
    }
}
