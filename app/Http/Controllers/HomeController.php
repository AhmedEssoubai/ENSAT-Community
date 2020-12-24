<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->isProfessor())
            return redirect()->route('classes');
        else
        {
            $class = 0;
            $c = Auth::user()->profile->classe;
            if (isset($c))
                $class = $c->id;
            return redirect()->route('classes.discussions', ['class' => $class]);
        }
    }

    /**
     * Show the application pending page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pending()
    {
        return view('pending');
    }
}
