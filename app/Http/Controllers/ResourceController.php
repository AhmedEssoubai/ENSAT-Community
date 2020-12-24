<?php

namespace App\Http\Controllers;

use App\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Resource;
use App\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ResourceController extends CommunityController
{

    /**
     * Show a discussions
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Classe $class, Request $request)
    {
        Gate::authorize('class-member', $class);
        
        $resources = $class->resources()->with(['class', 'course:id,short_title,color,class_id,professor_id', 'professor.user:id,firstname,lastname,image,profile_id,profile_type'])->orderBy('id', 'desc');
        $filter = 0;
        $search = null;
        if ($request->has('filter') && $request->filter != 0)
        {
            $filter = $request->filter;
            $resources->where('course_id', $filter);
        }
        if ($request->has('search') && !empty($request->search))
        {
            $search = $request->search;
            $resources->where('resources.title', 'like', '%'.$search.'%');
        }
        $resources = $resources->simplePaginate(20);
        return view('resource.index', [
            'class' => $class, 
            'students' => $this->studentsSample($class),
            //:id,short_title,color,professor.user
            'resources' => $resources,
            //Resource::where('class_id', $class->id)->with(['course:id,short_title,color,professor.user'])->get(), 
            'tw_assignments' => $this->thisWeekAssignments($class),
            'nw_assignments' => $this->nextWeekAssignments($class),
            'prof_courses' => $class->professorCourses(Auth::user()->profile_id),
            'filter' => $filter,
            'search' => $search,
            'sub_tab_index' => 1]);
    }

    
    /**
     * Create a new resource instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Resource
     */
    public function store(Request $request)
    {
        Gate::authorize('class-member', Classe::whereHas('courses', function (Builder $query) {
            $query->where('id', request('course'));
        })->findOrFail());
        
        $this->authorize('create', Resource::class);

        $request->validate([
            'title' => ['required', 'string', 'max:125'],
            'content' => ['required', 'string'],
            'course' => ['required', 'numeric', 'min:1']
        ]);
        $title = $request->input('title');
        $content = $request->input('content');
        $course = $request->input('course');
        /*$data = request()->validate([
            'title' => ['required', 'string', 'max:125'],
            'content' => ['required', 'string'],
            'course' => 'required',
            'attachments' => 'required'
        ]);*/

        $resource = Resource::create([
            'title' => $title,
            'content' => $content,
            'course_id' => $course
        ]);

        $this->saveAttachments($resource, $request->file('attachments'));

        return redirect()->back();
    }

    /**
     * Show a resource
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Resource $resource)
    {
        Gate::authorize('class-member', $resource->course->classe);
        return view('resource.show', ['resource' => $resource, 'user' => Auth::id(), 'class' => $resource->course->classe]);
    }

    /**
     * Show the edit resource form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);

        $resource->load('files:id,name,container_id,container_type');
        return view('resource.edit', ['class' => $resource->class, 
        'prof_courses' => $resource->class->professorCourses(Auth::user()->profile_id),
        'resource' => $resource, 
        'tab_index' => 0]);
    }

    /**
     * Update a resource
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Resource $resource, Request $request)
    {
        $this->authorize('update', $resource);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:125'],
            'content' => ['required', 'string'],
            'course_id' => ['required', 'numeric', 'min:1'],
            'eattachments' => ['array'],
            'eattachments.*' => ['integer', 'min:1']
        ]);

        $eattachments = $data['eattachments'];
        if (is_array($eattachments) || is_object($eattachments))
        {
            global $files;
            $files = collect([]);
    
            $resource->files->except($eattachments)->each(function ($file, $key) {
                global $files;
                $files->push($file->id);
                Storage::delete('uploads/resources/'.$file->url);
            });
    
            File::destroy($files);
        }

        $this->saveAttachments($resource, $request->file('attachments'));

        $resource->update($data);
        
        return redirect()->route('resources.show', ['resource' => $resource]);
    }

    /**
     * Delete a resource
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);

        global $files;
        $files = collect([]);

        $resource->files->each(function ($file, $key) {
            global $files;
            $files->push($file->id);
            Storage::delete('uploads/resources/'.$file->url);
        });

        File::destroy($files);

        $id = $resource->class->id;

        $resource->delete();
        
        return redirect(route('classes.resources', $id));
    }

    /**
     * Save the attached files of a resource
     */
    private function saveAttachments(Resource $resource, $attachments)
    {
        if (is_array($attachments) || is_object($attachments))
        {
            Storage::makeDirectory('upload/resources');
            foreach($attachments as $attachment)
            {
                $file = $attachment->getClientOriginalName();
                $name = pathinfo($file, PATHINFO_FILENAME) . '.' . pathinfo($file, PATHINFO_EXTENSION);
                $path = $attachment->store('uploads/resources');
                $parts = explode("/", $path);
                $resource->files()->create([
                    'url' => $parts[count($parts) - 1],
                    'name' => $name
                ]);
            }
        }
    }
}