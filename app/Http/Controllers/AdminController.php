<?php

namespace App\Http\Controllers;

use App\User;
use App\Professor;
use App\Student;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('not-pending');
        //$this->middleware('admin');
    }

    /**
     * Accept a user
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function acceptUser(User $user)
    {
        $user->update(['status' => 'membre']);
        
        return redirect()->back();
    }

    /**
     * Delete a user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroyUser(User $user)
    {
        $this->authorize('delete', $user);

        //$user->profile->delete();
        if ($user->isStudent())
        {
            $user->profile()->assignments()->detach();
            $user->profile()->submissions()->delete();
        }

        $user->delete();
        
        return redirect()->back();
    }

    /**
     * Show the list of professors
     */
    public function professors()
    {
        global $pending, $members;
        $pending = collect([]);
        $members = collect([]);
        $users = User::select('id', 'firstname', 'lastname', 'cin', 'image', 'status', 'profile_id')->where('profile_type', 'App\Professor')->with('profile')->orderBy('id', 'DESC')->get();
        $users->each(function ($user, $key) {
            global $pending, $members;
            if ($user->status == 'pending')
                $pending->push($user);
            else
                $members->push($user);
        });
        return view('admin.users', 
        ['pending' => $pending, 
        'members' => $members, 
        'user_tab_index' => 1]);
    }

    /**
     * Show the list of students
     */
    public function students()
    {
        global $pending, $members;
        $pending = collect([]);
        $members = collect([]);
        $users = User::select('id', 'firstname', 'lastname', 'cin', 'image', 'status', 'profile_id', 'profile_type')->where('profile_type', 'App\Student')->with('profile.classe:id,label')->orderBy('id', 'DESC')->get();
        $users->each(function ($user, $key) {
            global $pending, $members;
            if ($user->status == 'pending')
                $pending->push($user);
            else
                $members->push($user);
        });
        return view('admin.users', 
        ['pending' => $pending, 
        'members' => $members, 
        'user_tab_index' => 0]);
    }
}
