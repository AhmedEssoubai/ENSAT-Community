<?php

namespace App\Http\Controllers;

use App\Classe;
use App\Professor;
use App\User;
use Illuminate\Http\Request;

class ProfessorController extends Controller
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
     * Show all professors
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $professors = Professor::with('user')->get();
        $values = array();
        $ids = array();
        $imgs = array();
        foreach ($professors as $professor) {
            array_push($values, $professor->user->firstname." ".$professor->user->lastname);
            array_push($ids, $professor->id);
            array_push($imgs, $professor->user->image);
        }
        return response()->json(['values' => $values, 'ids' => $ids, 'imgs' => $imgs]);
    }

    /**
     * Search for a professor by first name or last name
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search($value, Request $request)
    {
        global $val;
        $val = $value;
        $users = User::where('profile_type', 'App\Professor')
                ->where(function($query) {
                    global $val;
                    $query->where('firstname', 'like', '%'.$val.'%')
                          ->orWhere('lastname', 'like', '%'.$val.'%');
                });
        $c = $request->input("class");
        if (isset($c))
        {
            $class = Classe::with('professors:id')->where('id', $c)->select('id')->first();
            if (isset($class))
            {
                $users_ids = array();
                foreach ($class->professors as $professor)
                    array_push($users_ids, $professor->id);
                $users->whereNotIn('profile_id', $users_ids);
            }
        }
        $users = $users->get(['firstname', 'lastname', 'image', 'profile_type', 'profile_id']);
        $names = array();
        $ids = array();
        $imgs = array();
        foreach ($users as $user) {
            /*if ($user->profile_type == 'App\Professor')
            {*/
                array_push($names, $user->firstname." ".$user->lastname);
                array_push($ids, $user->profile_id);
                array_push($imgs, $user->image);
            //}
        }
        return response()->json(['names' => $names, 'ids' => $ids, 'imgs' => $imgs]);
    }
}
