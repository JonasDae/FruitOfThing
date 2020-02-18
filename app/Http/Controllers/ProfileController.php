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

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        //Update user depending on what parameters are given
        if ($request->get('name')) { //Update current logged on user
            $data = $request->validate(array(
                'name' => array('required', 'string', 'max:255'),
            ));
            $user->update($data);
        } else { //Update other user access to the website
            $user->access = $request->get('access') == 'on' ? 1 : 0;
            $user->save();
        }

        return back()->with('message', array('status' => 'success', 'content' => 'Profiel bijgewerkt!'));
    }
}
