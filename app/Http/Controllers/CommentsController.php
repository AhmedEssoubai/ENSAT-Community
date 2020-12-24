<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
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
     * Create a new comment instance after a valid registration.
     *
     * @return \App\Comment
     */
    public function create(Request $request)
    {
        /*$data = request()->validate([
            'content' => ['required', 'string', 'max:255'],
            'post_id' => 'require'
        ]);*/

        $id = Auth::id();

        return response()->json(Comment::create([
            'content' => $request->input('content'),
            'discussion_id' => $request->input('discussion'),
            'user_id' => $id
        ]));
    }

    /**
     * Update a comment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Comment $comment, Request $request)
    {
        $this->authorize('update', $comment);

        return $comment->update(array_merge(['content' => $request->input('content')]));
    }

    /**
     * Delete a comment
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        return $comment->delete();
    }
}
