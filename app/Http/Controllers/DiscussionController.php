<?php

namespace App\Http\Controllers;

use App\Classe;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Discussion;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DiscussionController extends CommunityController
{
    /**
     * Show a discussions
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Classe $class, Request $request)
    {
        Gate::authorize('class-member', $class);

        $filter_1 = 0;
        $filter_2 = -1;
        $search = null;
        $discussions = Discussion::where('class_id', $class->id)->with(['user.profile', 'course:id,short_title,color'])->withCount(['favorited_users', 'comments']);
        if ($request->has('filter_1') && $request->filter_1 != 0)
        {
            $filter_1 = $request->filter_1;
            if ($filter_1 == 1)
                $discussions->orderBy('favorited_users_count', 'desc');
            else
                $discussions->doesntHave('comments')->orderBy('id', 'desc');
        }
        else
            $discussions->orderBy('id', 'desc');
        if ($request->has('filter_2') && $request->filter_2 != -1)
        {
            $filter_2 = $request->filter_2;
            if ($filter_2 == 0)
                $discussions->where('course_id', null);
            else
                $discussions->where('course_id', $filter_2);
        }
        if ($request->has('search') && !empty($request->search))
        {
            $search = $request->search;
            $discussions->where('title', 'like', '%'.$search.'%');
        }
        $discussions = $discussions->simplePaginate(20);
        return view('discussion.index', [
            'class' => $class, 
            'students' => $this->studentsSample($class),
            'discussions' => $discussions, 
            'tw_assignments' => $this->thisWeekAssignments($class),
            'nw_assignments' => $this->nextWeekAssignments($class),
            'filter_1' => $filter_1,
            'filter_2' => $filter_2,
            'search' => $search,
            'sub_tab_index' => 0]);
    }

    
    /**
     * Create a new discussion instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Discussion
     */
    public function store()
    {
        Gate::authorize('class-member', Classe::findOrFail(request('class')));

        $data = request()->validate([
            'class' => 'required',
            'title' => ['required', 'string', 'max:125', 'min:6'],
            'content' => ['required', 'string', 'min:12'],
            'class' => ['required', 'numeric', 'min:1'],
            'course' => ['required', 'numeric', 'min:0'],
            'image' => ['image', 'max:512']
        ]);
        $course = null;
        if ($data['course'] > 0)
            $course = $data['course'];
        
        $path = null;

        if (!empty($data['image']))
            $path = $data['image']->store('uploads/discussion', 'public');

        $id = Auth::id();

        Discussion::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'class_id' => $data['class'],
            'course_id' => $course,
            'image' => $path,
            'user_id' => $id
        ]);

        return redirect()->back();

        //return redirect()->route('groupes.show', ['groupe' => $data['groupe_id']]);
    }

    /**
     * Show a discussion
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($discussion)
    {
        $_discussion = Discussion::with(['class', 'user:id,firstname,lastname,image', 'course:id,short_title,color', 'favorited_users:id'])->findOrFail($discussion);

        Gate::authorize('class-member', $_discussion->class);
        return view('discussion.show', ['discussion' => $_discussion, 'user' => Auth::user(), 
        'comments' => Comment::select('id', 'content', 'user_id', 'created_at', 'discussion_id')->with('user:id,firstname,lastname,image')->where('discussion_id', $discussion)->orderBy('id', 'DESC')->get(),
        'class' => $_discussion->class]);
    }

    /**
     * Favorite a discussion or remove favorite from him if already exists
     */
    public function favorite(Discussion $discussion)
    {
        Gate::authorize('class-member', $discussion->class);

        return $discussion->favorited_users()->toggle(Auth::user());
        
        //return response()->json(['message' => 'Not a member'], 200);
    }

    /**
     * Bookmark a discussion or remove it from bookmark list if already exists
     */
    public function bookmark(Discussion $discussion)
    {
        Gate::authorize('class-member', $discussion->class);

        return $discussion->bookmarked_users()->toggle(Auth::user());
    }

    /**
     * Show the edit discussion form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        return view('discussion.edit', ['class' => Classe::with('courses:id,title,class_id')->where('id', $discussion->class_id)->first(), 
        'discussion' => $discussion, 'tab_index' => 0]);
    }

    /**
     * Update a discussion
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        $data = request()->validate([
            'title' => ['required', 'string', 'max:125', 'min:6'],
            'content' => ['required', 'string', 'min:12'],
            'course' => ['required', 'numeric', 'min:0'],
            'image' => ['image', 'max:512']
        ]);

        if ($data['course'] == 0)
            $discussion->course_id = null;
        else
            $discussion->course_id = $data['course'];
        
        $discussion->title = $data['title'];
        $discussion->content = $data['content'];

        if (!empty($data['image']))
        {
            Storage::delete('public/'.$discussion->image);
            $discussion->image = $data['image']->store('uploads/discussion', 'public');
        }

        if ($discussion->isDirty())
            $discussion->save();
        
        return redirect()->route('discussions.show', ['discussion' => $discussion]);
    }

    /**
     * Delete a discussion
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Discussion $discussion)
    {
        $this->authorize('delete', $discussion);

        $id = $discussion->class_id;

        $discussion->delete();
        
        return redirect(route('classes.discussions', $id));
    }

}
