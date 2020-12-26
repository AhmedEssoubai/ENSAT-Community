<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
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
     * Get the assigned students and there submissions
     */
    public function index(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);
        global $id;
        $id = $assignment->id;
        $assigneds = null;
        if ($assignment->all)
        {
            if ($assignment->to_groups)
                $assigneds = $assignment->class->groups;
            else
                $assigneds = $assignment->class->activeStudents()->with('user:id,firstname,lastname,image,profile_id,profile_type')->get();
        }
        else
        {
            if ($assignment->to_groups)
                $assigneds = $assignment->groups;
            else
                $assigneds = $assignment->students()->with('user:id,firstname,lastname,image,profile_id,profile_type')->get();
        }
        $submissions = $assignment->submissions()->with('files')->get();
        $names = array();
        $ids = array();
        $imgs = array();
        $dates = array();
        $files = array();
        foreach ($assigneds as $assigned) {
            $id = 0;
            if ($assignment->to_groups)
            {
                array_push($names, $assigned->label);
                array_push($ids, $assigned->id);
                $id = $assigned->id;
            }
            else
            {
                array_push($names, $assigned->user->firstname." ".$assigned->user->lastname);
                array_push($ids, $assigned->user->profile_id);
                array_push($imgs, $assigned->user->image);
                $id = $assigned->user->profile_id;
            }
            $exists = false;
            foreach ($submissions as $submission)
            {
                if ($submission->submitter_id == $id)
                {
                    $submission_files = array();
                    foreach ($submission->files as $file)
                        $submission_files += [$file->id => $file->name];
                    $files += [$id => $submission_files];
                    array_push($dates, $submission->created_at);
                    $exists = true;
                    break;
                }
            }
            if (!$exists)
                array_push($dates, null);
        }
        return response()->json(['names' => $names, 
                                'ids' => $ids, 
                                'imgs' => $imgs, 
                                'dates' => $dates, 
                                'files' => $files]);
    }
    
    /**
     * Create a new assignment instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\assignment
     */
    public function store(Request $request)
    {
        $assignment = Assignment::findOrFail(request('assignment'));
        if (Auth::user()->isStudent() && !$assignment->isAssignedTo(Auth::user()->profile_id))
            abort(403, 'The work is not assigned to you.');
        if ($assignment->isClosed())
            abort(403, 'The assignment is closed for submission');
        $data = request()->validate([
            'assignment' => ['required', 'integer'],
            'attachments' => ['required', 'array'],
            'attachments.*' => ['file', 'max:22000']
        ]);

        $attachments = $request->file('attachments');

        if ($assignment->to_groups)
            $submitter = $assignment->groups()->whereHas('students', function (Builder $query) {
                $query->where('students.id', Auth::user()->profile_id);
            })->first();
        else
            $submitter = Auth::user()->profile;
        
        $submission = $submitter->submissions()->create([
            'assignment_id' => $assignment->id,
        ]);

        foreach($attachments as $attachment)
        {
            $file = $attachment->getClientOriginalName();
            $name = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
            $path = $attachment->store('uploads/submissions');
            $parts = explode("/", $path);
            $submission->files()->create([
                'url' => $parts[count($parts) - 1],
                'name' => $name
            ]);
        }
        return redirect()->back();

        /*$assignment = Submission::create([
            'title' => $data['title'],
            'objectif' => $data['objectif'],
            'deadline' => $data['deadline'],
            'course_id' => $data['course'],
            'to_groups' => intval($data['assigned_type']) == 1,
            'all' => isset($data['assigned_all'])
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
            Storage::makeDirectory('upload/assignment');
            foreach($attachments as $attachment)
            {
                $file = $attachment->getClientOriginalName();
                $name = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
                $path = $attachment->store('uploads/assignment', 'public');
                $parts = explode("/", $path);
                $assignment->files()->create([
                    'url' => $parts[count($parts) - 1],
                    'name' => $name
                ]);
            }
        }*/
        //return redirect()->back();
    }

    /**
     * Show an assignment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Assignment $assignment)
    {
        return view('assignment.show', ['assignment' => $assignment, 'user' => Auth::id(), 'class' => $assignment->course->classe]);
    }

    /**
     * Show the edit assignment form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Assignment $assignment)
    {
        //$this->authorize('update', $assignment);

        return view('assignment.edit', ['assignment' => $assignment]);
    }

    /**
     * Update a assignment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Assignment $assignment)
    {
        //$this->authorize('update', $assignment);

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
        //$this->authorize('delete', $assignment);

        $id = $assignment->class_id;

        $assignment->delete();
        
        return redirect('/classes/' . $id . '/assignments');
    }
}