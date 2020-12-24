<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
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
    
    public function studentsSample($class)
    {
        return Student::select('id', 'user_id')->where('class_id', $class->id)->with('user:id,firstname,lastname,image,profile_id')->orderBy('id', 'desc')->take(5)->get();
    }

    public function thisWeekAssignments($class)
    {
        if (Auth::user()->isProfessor())
            return collect([]);
        $now = Carbon::now();
        return $class->assignments()->select('assignments.id', 'assignments.title', 'deadline')/*->where('class_id', $class->id)*/
                ->whereDoesntHave('submissions', function (Builder $query) {
                    $query->where('submitter_id', Auth::user()->profile_id);
                })
                ->where('deadline', '>', $now->toDateTimeString())
                ->whereBetween('deadline', [Carbon::now()->startOfWeek(), $now->endOfWeek()])
                ->orderBy('deadline')->take(5)->get();
    }

    public function nextWeekAssignments($class)
    {
        if (Auth::user()->isProfessor())
            return collect([]);
        $now = Carbon::now();
        return $class->assignments()->select('assignments.id', 'assignments.title', 'deadline')
                ->whereDoesntHave('submissions', function (Builder $query) {
                    $query->where('submitter_id', Auth::user()->profile_id);
                })
                ->where('deadline', '>', $now->toDateTimeString())
                ->whereBetween('deadline', [Carbon::now()->addWeek()->startOfWeek(), $now->addWeek()->endOfWeek()])
                ->orderBy('deadline')->take(5)->get();
    }
}
