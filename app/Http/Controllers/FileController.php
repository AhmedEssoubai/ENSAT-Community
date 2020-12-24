<?php

namespace App\Http\Controllers;

use App\Classe;
use App\File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
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
     * Download resource file
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function resource(File $file)
    {
        global $f;
        $f = $file;
        Gate::authorize('class-member', Classe::whereHas('resources', function (Builder $query) {
            global $f;
            $query->where('resources.id', $f->container_id);
        })->first());
        return Storage::download('uploads/resources/' . $file->url, $file->name);
    }

    /**
     * Download assignment file
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function assignment(File $file)
    {
        global $f;
        $f = $file;
        Gate::authorize('class-member', Classe::whereHas('assignments', function (Builder $query) {
            global $f;
            $query->where('assignments.id', $f->container_id);
        })->first());
        return Storage::download('uploads/assignments/' . $file->url, $file->name);
    }

    /**
     * Download submission file
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function submission(File $file)
    {
        global $submission;
        $submission = $file->container;
        Gate::authorize('class-member', Classe::whereHas('assignments', function (Builder $query) {
            global $submission;
            $query->where('assignments.id', $submission->assignment_id);
        })->first());
        return Storage::download('uploads/submissions/' . $file->url, $file->name);
    }
}
