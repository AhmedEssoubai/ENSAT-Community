<?php

namespace App\Http\Controllers;

use App\User;
use App\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StudentController extends Controller
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
     * Search for a student by first name or last name
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search($class, $value)
    {
        global $v, $c;
        $v = $value;
        $c = $class;
        $users = User::where(function($query) {
                        global $v;
                        $query->where('firstname', 'like', '%'.$v.'%')
                            ->orWhere('lastname', 'like', '%'.$v.'%');
                    })->where('status', 'membre')
                    ->where('profile_type', 'App\Student')
                    ->whereHasMorph('profile', 'App\Student', function(Builder $query){
                        global $c;
                        $query->where('class_id', $c);
                    })->get(['id', 'firstname', 'lastname', 'image', 'profile_id', 'profile_type']);
        $names = array();
        $ids = array();
        $imgs = array();
        foreach ($users as $user) {
            array_push($names, $user->firstname." ".$user->lastname);
            array_push($ids, $user->profile_id);
            array_push($imgs, $user->image);
        }
        return response()->json(['names' => $names, 'ids' => $ids, 'imgs' => $imgs]);
    }

    /**
     * Kick a student
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function kick(Student $student)
    {
        $student->user->update(['status' => 'pending']);
        
        return redirect()->back();
    }
}
