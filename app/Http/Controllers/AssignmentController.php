<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\File;
use App\Group;
use App\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AssignmentController extends CommunityController
{

    /**
     * Show a discussions
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Classe $class, Request $request)
    {
        Gate::authorize('class-member', $class);
        // Create assignments startup query
        $assignments = $class->assignments()->with(['course:id,short_title,color,class_id,professor_id', 'professor.user:id,firstname,lastname,image,profile_id,profile_type']);
        // Load students and groups for assigning
        $students_ids = [];
        $students_names = [];
        $groups_ids = [];
        $groups_names = [];
        if (Auth::user()->isProfessor())
        {
            foreach(Student::where('class_id', $class->id)
            ->with('user:id,firstname,lastname,profile_id,profile_type')->get() as $student){
                array_push($students_ids, $student->id);
                array_push($students_names, $student->user->firstname . ' ' . $student->user->lastname);
            }
            foreach(Group::where('class_id', $class->id)->get() as $group){
                array_push($groups_ids, $group->id);
                array_push($groups_names, $group->label);
            }
            $assignments->withCount('submissions');
        }
        else
        {
            global $has_groups;
            $has_groups = $class->groups()->whereHas('students', function (Builder $query) {
                $query->where('id', Auth::user()->profile_id);
            })->select('id', 'class_id')->exists();
            $assignments->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('to_groups', false)
                        ->where(function ($query) {
                            $query->where('all', true)
                            ->orWhere(function ($query) {
                                $query->/*whereExists*/whereHas('students', function (Builder $query) {
                                    $query->where('id', Auth::user()->profile_id);
                                });
                            });
                        });
                })->orWhere(function ($query) {
                    $query->where('to_groups', true)
                        ->where(function ($query) {
                            global $has_groups;
                            if ($has_groups)
                                $query->where('all', true)
                                    ->orWhere(function ($query) {
                                    $query->whereHas('groups', function (Builder $query) {
                                        $query->whereHas('students', function (Builder $query) {
                                            $query->where('id', Auth::user()->profile_id);
                                        });
                                    });
                                });
                            else
                                $query->where(function ($query) {
                                    $query->where('all', false)
                                        ->whereHas('groups', function (Builder $query) {
                                        $query->whereHas('students', function (Builder $query) {
                                            $query->where('id', Auth::user()->profile_id);
                                        });
                                    });
                                });
                        });
                });
            })->withCount(['submissions' => function (Builder $query) {
                $query->where('submitter_id', Auth::user()->profile_id);
            }]);
        }
        // Filter data
        $filter_1 = 0;
        $filter_2 = 0;
        $search = null;
        if ($request->has('filter_1') && $request->filter_1 != 0)
        {
            $filter_1 = $request->filter_1;
            // Missing / Closed
            if ($filter_1 == 1)
                $assignments->where('deadline', '<=', Carbon::now()->toDateTimeString())->orderBy('id', 'desc');
            // Submitted / All Submitted
            else if ($filter_1 == 2)
                if (Auth::user()->isProfessor())
                    //$assignments->withCount('submissions')->whereColumn('submissions_count', 'assigned_to_count')->orderBy('id', 'desc');
                    $assignments->has('submissions', DB::raw('assigned_to_count'))->orderBy('id', 'desc');
                else
                    $assignments->whereHas('submissions', function (Builder $query) {
                        $query->where('submitter_id', Auth::user()->profile_id);
                    })->orderBy('id', 'desc');
            // Near
            else
            {
                $assignments->where('deadline', '>', Carbon::now()->toDateTimeString())
                    ->where('deadline', '<=', Carbon::now()->addDays(2)->toDateTimeString())
                    ->orderBy('deadline');
                if (Auth::user()->isStudent())
                    $assignments->whereDoesntHave('submissions', function (Builder $query) {
                        $query->where('submitter_id', Auth::user()->profile_id);
                    });
            }
        }
        else
            $assignments->orderBy('id', 'desc');
        if ($request->has('filter_2') && $request->filter_2 != 0)
        {
            $filter_2 = $request->filter_2;
            $assignments->where('course_id', $filter_2);
        }
        if ($request->has('search') && !empty($request->search))
        {
            $search = $request->search;
            $assignments->where('assignments.title', 'like', '%'.$search.'%');
        }
        $assignments = $assignments->simplePaginate(20);
        return view('assignment.index', ['class' => $class, 
                                'assignments' => $assignments,
                                'prof_courses' => $class->professorCourses(Auth::user()->profile_id), 
                                'sub_tab_index' => 2, 
                                'students_ids' => $students_ids, 
                                'students_names' => $students_names, 
                                'students' => $this->studentsSample($class),
                                'tw_assignments' => $this->thisWeekAssignments($class),
                                'nw_assignments' => $this->nextWeekAssignments($class),
                                'filter_1' => $filter_1,
                                'filter_2' => $filter_2,
                                'search' => $search,
                                'groups_ids' => $groups_ids, 
                                'groups_names' => $groups_names]);
    }

    
    /**
     * Create a new assignment instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\assignment
     */
    public function store(Request $request)
    {
        $class = Classe::whereHas('courses', function (Builder $query) {
            $query->where('id', request('course'));
        })->first();
        Gate::authorize('class-member', $class);
        
        $this->authorize('create', Assignment::class);

        $data = request()->validate([
            'title' => ['required', 'string', 'max:125'],
            'objectif' => ['required', 'string'],
            'deadline' => ['required', 'date'],
            'course' => ['required', 'numeric', 'min:1'],
            'assigned_type' => ['required', 'integer'],
            'assigned_all' => 'boolean',
            'targets' => 'array'
        ]);

        $assigned_to_count = 0;
        if (isset($data['assigned_all']))
            if ($data['assigned_type'] == 1)
                $assigned_to_count = $class->groups()->count();
            else
                $assigned_to_count = $class->activeStudents()->count();
        else 
            if (isset($data['targets']) && count($data['targets']) > 0)
                $assigned_to_count = count($data['targets']);
            else
                throw ValidationException::withMessages(['targets' => 'They must be some targets']);

        $assignment = Assignment::create([
            'title' => $data['title'],
            'objectif' => $data['objectif'],
            'deadline' => $data['deadline'],
            'course_id' => $data['course'],
            'to_groups' => intval($data['assigned_type']) == 1,
            'all' => isset($data['assigned_all']),
            'assigned_to_count' => $assigned_to_count
        ]);

        if (!isset($data['assigned_all']) && isset($data['targets']))
        {
            if ($data['assigned_type'] == 1)
                $assignment->groups()->attach($data['targets']);
            else
                $assignment->students()->attach($data['targets']);
        }

        $attachments = $request->file('attachments');

        if (is_array($attachments) || is_object($attachments))
        {
            foreach($attachments as $attachment)
            {
                $file = $attachment->getClientOriginalName();
                $name = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
                $path = $attachment->store('uploads/assignments');
                $parts = explode("/", $path);
                $assignment->files()->create([
                    'url' => $parts[count($parts) - 1],
                    'name' => $name
                ]);
            }
        }
        return redirect()->back();
    }

    /**
     * Show an assignment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Assignment $assignment)
    {
        Gate::authorize('class-member', $assignment->course->classe);
        if (Auth::user()->isStudent())
        {
            if (!$assignment->isAssignedTo(Auth::user()->profile_id))
                abort(403, 'The work is not assigned to you.');
            $assignment->views()->syncWithoutDetaching(Auth::user()->profile_id);
        }
        return view('assignment.show', 
        ['assignment' => $assignment, 
        'submission' => $assignment->getSubmission(Auth::user()),
        'user' => Auth::id(), 
        'class' => $assignment->course->classe]);
    }

    /**
     * Show the edit assignment form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        return view('assignment.edit', ['assignment' => $assignment]);
    }

    /**
     * Update a assignment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $data = request()->validate([
            'titre' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['image']
        ]);

        if (!empty($data['image']))
        {
            Storage::delete($assignment->image);
            $image = ['image' => ($data['image']->store('uploads', 'public'))];
        }

        $assignment->update(array_merge($data, $image ?? []));
        
        return redirect()->route('assignment.show', ['assignment' => $assignment]);
    }

    /**
     * Delete a assignment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        global $files;
        $files = collect([]);

        $assignment->files->each(function ($file, $key) {
            global $files;
            $files->push($file->id);
            Storage::delete('uploads/assignments/'.$file->url);
        });
        $assignment->submissions()->files->each(function ($file, $key) {
            global $files;
            $files->push($file->id);
            Storage::delete('uploads/submissions/'.$file->url);
        });

        File::destroy($files);

        $id = $assignment->class_id;

        $assignment->delete();
        
        return redirect('/classes/' . $id . '/assignments');
    }

    public function studentsViewHistory(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);
        global $id;
        $id = $assignment->id;
        $students = null;
        if ($assignment->all)
        {
            if ($assignment->to_groups)
                $students = $assignment->class->activeStudents()->whereHas('groups');
            else
                $students = $assignment->class->activeStudents()->whereHas('groups');
        }
        else
        {
            if ($assignment->to_groups)
                $students = $assignment->class->activeStudents()->whereHas('groups', function (Builder $query) {
                    $query->whereHas('assignments', function (Builder $query) {
                        global $id;
                        $query->where('id', $id);
                    });
                });
            else
                $students = $assignment->students();
        }
        $students = $students->with('user:id,firstname,lastname,image,profile_id,profile_type')->get();
        $views = $assignment->views()->where('seen_id', $assignment->id)->select('id')->get();
        $files = $assignment->files()->select('id')->with('views:id')->get();
        $names = array();
        $ids = array();
        $imgs = array();
        $dates = array();
        $files_history = array();
        foreach ($students as $student) {
            array_push($names, $student->user->firstname." ".$student->user->lastname);
            array_push($ids, $student->user->profile_id);
            array_push($imgs, $student->user->image);
            $view = $views->find($student->user->profile_id);
            array_push($dates, $view ? $view->pivot->seen_at : null);
            $student_files_history = array();
            foreach ($files as $file)
            {
                $fview = $file->views->find($student->user->profile_id);
                array_push($student_files_history, $fview ? $fview->pivot->seen_at : null);
            }
            array_push($files_history, $student_files_history);
        }
        return response()->json(['names' => $names, 
                                'ids' => $ids, 
                                'imgs' => $imgs, 
                                'dates' => $dates, 
                                'files_history' => $files_history]);
    }
}
