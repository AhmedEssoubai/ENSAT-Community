<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Classe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $classes = null;
        if (Auth::user()->isProfessor())
        {
            if (Auth::user()->isAdmin())
            {
                $classes = Classe::select(['id', 'label'])->get();
                $dbannouncements = Announcement::orderBy('created_at', 'DESC');
            }
            else
            {
                $classes = Auth::user()->isAdmin() ?? Auth::user()->profile()->classes()->select(['id', 'label'])->get();
                $dbannouncements = Announcement::whereHas('classes', function (Builder $query) {
                    $query->whereIn('id', []);
                })->orderBy('created_at', 'DESC');
            }
        }
        else
            $dbannouncements = Announcement::whereHas('classes', function (Builder $query) {
                $query->where('id', Auth::user()->profile->class_id);
            })->orderBy('created_at', 'DESC');
        $announcements = collect();
        $dbannouncements = $dbannouncements->with('classes:id,label', 'professor.user:id,firstname,lastname,profile_id,profile_type')->get();
        $day_announcements = collect();
        $day = null;
        $count = $dbannouncements->count();
        foreach ($dbannouncements as $announcement)
        {
            if ($announcement->created_at->toDateString() != $day)
            {
                if ($day != null)
                {
                    $announcements->push($day_announcements);
                    $day_announcements = collect();
                    $day = $announcement->created_at->toDateString();
                }
                $day = $announcement->created_at->toDateString();
            }
            $day_announcements->push($announcement);
        }
        if ($dbannouncements->count() > 0)
            $announcements->push($day_announcements);
        return view('announcement.index', ['classes' => $classes, 'announcements' => $announcements, 'announcements_count' => $count]);
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
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['required', 'numeric', 'min:1']
        ]);

        $title = $request->input('title');
        $content = $request->input('content');
        $classes = $request->input('classes');

        $announcement = Announcement::create([
            'title' => $title,
            'content' => $content,
            'professor_id' => Auth::user()->profile_id
        ]);

        $announcement->classes()->attach($classes);

        return redirect()->back();
    }

    /**
     * Show a announcement
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Announcement $announcement)
    {
        //Gate::authorize('class-member', $announcement->course->classe);
        return view('announcement.show', ['announcement' => $announcement]);
    }

    /**
     * Show the edit announcement form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        if (Auth::user()->isAdmin())
            $classes = Classe::select(['id', 'label'])->get();
        else
            $classes = Auth::user()->isAdmin() ?? Auth::user()->profile()->classes()->select(['id', 'label'])->get();
        $announcement->load('classes:id');
        return view('announcement.edit', ['classes' => $classes, 
        'announcement' => $announcement]);
    }

    /**
     * Update a announcement
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Announcement $announcement, Request $request)
    {
        $this->authorize('update', $announcement);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:125'],
            'content' => ['required', 'string'],
            'classes' => ['required', 'array', 'min:1'],
            'classes.*' => ['required', 'numeric', 'min:1']
        ]);

        $announcement->load('classes:id');

        $del = $announcement->classes->except($data['classes']);
        $new = array();
        foreach ($data['classes'] as $class)
            if (!$announcement->classes->contains($class))
                array_push($new, $class);

        $announcement->classes()->detach($del);
        $announcement->classes()->attach($new);

        $announcement->update($data);
        
        return redirect()->route('announcements.show', ['announcement' => $announcement]);
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
