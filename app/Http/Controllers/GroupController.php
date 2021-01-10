<?php

namespace App\Http\Controllers;

use App\Classe;
use App\File;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
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
     * Show the list of groups for a class
     *
     */
    public function index($class)
    {
        return view('group.index', ['class' => Classe::with(['groups.students.user:id,firstname,lastname,image,profile_id,profile_type'])->findOrFail($class), 'tab_index' => 3]);
    }

    /**
     * Create a new group instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function store()
    {
        Gate::authorize('class-member', Classe::findOrFail(request('class')));
        $data = request()->validate([
            'label' => ['required', 'string', 'max:255'],
            'class' => ['required', 'numeric', 'min:1'],
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['required', 'numeric', 'min:1']
        ]);
        $g = Group::create([
            'label' => $data['label'],
            'class_id' => $data['class']
        ]);
        $g->students()->attach($data['students']);
        return redirect()->back();
    }
    /**
     * Show the edit group form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Group $group)
    {
        Gate::authorize('class-member', $group->classe);
        $this->authorize('update', $group);
        $group->load('students.user:id,firstname,lastname,image,profile_id,profile_type');
        return view('group.edit', ['class' => $group->classe, 'group' => $group, 'tab_index' => 3]);
    }

    /**
     * Update a group
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Group $group)
    {
        Gate::authorize('class-member', $group->classe);
        $this->authorize('update', $group);

        $data = request()->validate([
            'label' => ['required', 'string', 'max:255'],
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['required', 'numeric', 'min:1']
        ]);

        $group->load('students:id');

        $del = $group->students->except($data['students']);
        $new = array();
        foreach ($data['students'] as $student)
            if (!$group->students->contains($student))
                array_push($new, $student);

        $group->students()->detach($del);
        $group->students()->attach($new);

        $group->update($data);
        
        return redirect()->route('classes.groups', ['class' => $group->class_id]);
    }

    /**
     * Delete a group
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Group $group)
    {
        Gate::authorize('class-member', $group->classe);
        $this->authorize('delete', $group);

        $group->assignments()->detach();

        global $files;
        $files = collect([]);
        $group->submissions()->files()->get()->each(function ($file, $key) {
            global $files;
            $files->push($file->id);
            Storage::delete('uploads/submissions/'.$file->url);
        });
        File::destroy($files);

        $group->submissions()->delete();
        
        $group->delete();
        
        return back();
    }
}
