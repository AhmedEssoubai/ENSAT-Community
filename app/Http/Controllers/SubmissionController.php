<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Submission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('student');
    }
    
    /**
     * Create a new assignment instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\assignment
     */
    public function store(Request $request)
    {
        if (Auth::user()->isStudent() && !Assignment::findOrFail(request('assignment'))->isAssignedTo(Auth::user()->profile_id))
            abort(403, 'The work is not assigned to you.');
        $data = request()->validate([
            'assignment' => ['required', 'integer'],
            'attachments' => ['required', 'array'],
            'attachments.*' => ['file', 'max:22000']
        ]);

        $attachments = $request->file('attachments');

        $assignment = Assignment::find($data['assignment']);
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