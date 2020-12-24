<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
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
    }

    /**
     * Show the edit profile form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update a profile
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(User $user)
    {
        $this->authorize('update', $user);

        $data = request()->validate([
            'firstname' => ['required', 'string', 'max:20'],
            'lastname' => ['required', 'string', 'max:20'],
            'cin' => ['required', 'string', 'max:10', 'unique:users,cin,'.$user->id],
            'image' => ['image', 'max:100']
        ]);

        if (!empty($data['image']))
        {
            if ($user->image != '/img/default.png')
                Storage::delete('public/'.Str::substr($user->image, 9, Str::of($user->image)->length() - 9));
            $image = ['image' => '/storage/'.($data['image']->store('uploads/profile', 'public'))];
        }

        $user->update(array_merge($data, $image ?? []));
        
        return redirect(route('profile.edit', $user->cin));
    }

    

    /**
     * Update password
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateSecurity(User $user)
    {
        $this->authorize('update', $user);

        $data = request()->validate([
            'current-password' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if (!Hash::check($data['current-password'], auth()->user()->password))
            throw ValidationException::withMessages(['current-password' => 'Incorrect password']);

        if (Hash::check($data['password'], auth()->user()->password))
            throw ValidationException::withMessages(['password' => 'Invalid new password']);

        $user->update(['password' => Hash::make($data['password'])]);
    
        return redirect(route('profile.edit', $user->cin));
    }
}
