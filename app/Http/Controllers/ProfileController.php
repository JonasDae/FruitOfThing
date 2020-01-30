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

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate(array(
            'name' => array('required', 'string', 'max:255'),
        ));

        $user->update($data);

        return redirect(route('profile.index'));
    }
}
