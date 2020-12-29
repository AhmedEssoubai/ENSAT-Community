<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
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
     * Show a discussions
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('announcement.index', ['day_announcements' => []]);
    }
    
    /**
     * Create a new resource instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Resource
     */
    public function store(Request $request)
    {
        $this->authorize('create', Announcement::class);

        $request->validate([
            'title' => ['required', 'string', 'max:125'],
            'content' => ['required', 'string'],
            'classes' => ['required', 'array'],
            'classes.*' => ['required', 'numeric', 'min:1']
        ]);

        $title = $request->input('title');
        $content = $request->input('content');
        $classes = $request->input('classes');

        $announcement = Announcement::create([
            'title' => $title,
            'content' => $content
        ]);

        return redirect()->back();
    }

    /**
     * Show a announcement
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Announcement $announcement)
    {
        /*Gate::authorize('class-member', $announcement->course->classe);
        if (Auth::user()->isStudent())
            $announcement->views()->syncWithoutDetaching(Auth::user()->profile_id);
        return view('resource.show', ['resource' => $resource, 'user' => Auth::id(), 'class' => $resource->course->classe]);*/
    }

    /**
     * Show the edit announcement form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Announcement $announcement)
    {
        /*$this->authorize('update', $resource);

        $resource->load('files:id,name,container_id,container_type');
        return view('resource.edit', ['class' => $resource->class, 
        'prof_courses' => $resource->class->professorCourses(Auth::user()->profile_id),
        'resource' => $resource, 
        'tab_index' => 0]);*/
    }

    /**
     * Update a announcement
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Announcement $announcement, Request $request)
    {
        /*$this->authorize('update', $resource);

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
                $files->views()->detach();
                Storage::delete('uploads/resources/'.$file->url);
            });
    
            File::destroy($files);
        }

        $this->saveAttachments($resource, $request->file('attachments'));

        $resource->update($data);
        
        return redirect()->route('resources.show', ['resource' => $resource]);*/
    }

    /**
     * Delete a announcement
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();
        
        return back();
    }
}
