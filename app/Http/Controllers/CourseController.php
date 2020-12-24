<?php

namespace App\Http\Controllers;

use App\Course;
use App\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
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
     * Show the list of courses
     *
     */
    public function index(Classe $class)
    {
        Gate::authorize('class-member', $class);
        return view('course.index', 
        ['class' => $class, 
        'courses' => Course::select('id', 'title', 'description', 'color', 'professor_id', 'class_id')
                        ->where('class_id', $class->id)->with('professor.user:id,firstname,lastname,profile_id,profile_type')
                        ->withCount(['resources', 'assignments'])
                        ->get(),
        'tab_index' => 2]);
    }

    /**
     * Create a new group instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function store()
    {
        Gate::authorize('class-member', Classe::findOrFail(request('class_id')));
        $data = request()->validate([
            'title' => ['required', 'string', 'max:125'],
            'short_title' => ['required', 'string', 'max:10'],
            'description' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:10'],
            'class_id' => ['required', 'numeric', 'min:1'],
        ]);
    
        //$id = Auth::user()->profile->id;

        Course::create([
            'title' => $data['title'],
            'short_title' => $data['short_title'],
            'description' => $data['description'],
            'color' => $data['color'],
            'class_id' => $data['class_id'],
            'professor_id' => Auth::user()->profile_id
        ]);
        return redirect()->back();
    }

    /**
     * Show the edit course form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        return view('course.edit', ['class' => $course->classe, 'course' => $course, 'tab_index' => 2]);
    }

    /**
     * Update a course
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Course $course)
    {
        $this->authorize('update', $course);

        $data = request()->validate([
            'title' => ['required', 'string', 'max:125'],
            'short_title' => ['required', 'string', 'max:10'],
            'description' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:10']
        ]);

        $course->update($data);
        
        return redirect()->route('classes.courses', ['class' => $course->class_id]);
    }

    /**
     * Delete a course
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();
        
        return back();
    }
}
