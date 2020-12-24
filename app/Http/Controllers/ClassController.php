<?php

namespace App\Http\Controllers;

use App\Classe;
use App\Professor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClassController extends Controller
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
     * Show the list of classes
     *
     */
    public function index()
    {
        $professors = null;
        $my_classes = null;
        $classes = null;
        global $user;
        $user = Auth::user();
        if ($user->isAdmin())
        {
            $professors = Professor::with('user:id,firstname,lastname,profile_id,profile_type')->get();
            $classes = Classe::withCount(['students', 'resources', 'assignments'])->get();
            $my_classes = $classes->intersect(Classe::whereHas('professors', function($query){
                global $user;
                $query->where('id', $user->profile_id);
            })->select('id')->get());
        }
        else
            $my_classes = Classe::whereHas('professors', function($query){
                global $user;
                $query->where('id', $user->profile_id);
            })->withCount(['students', 'resources', 'assignments'])->get();
        return view('class.index', ['my_classes' => $my_classes, 'all_classes' => $classes, 'professors' => $professors]);
    }

    /**
     * Create a new group instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function store()
    {
        $this->authorize('create', Classe::class);
        $data = request()->validate([
            'label' => ['required', 'string', 'max:255'],
            'chef' => ['required', 'numeric', 'min:1']
        ]);
        $c = Classe::create([
            'label' => $data['label'],
            'chef_id' => $data['chef'],
            'image' => '/img/class-cover.jpg'
        ]);
        $c->professors()->attach($data['chef']);
        return redirect()->route('classes.discussions', ['class' => $c->id]);
    }

    /**
     * Show a class
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($class)
    {
        return view('class.show', ['class' => Classe::findOrFail($class)]);
    }

    /**
     * Show a create class form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('class.create');
    }

    /**
     * Show the edit class form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Classe $class)
    {
        $this->authorize('update', $class);

        return view('class.edit', ['class' => $class, 
        'tab_index' => 4,
        'professors' => Professor::with('user:id,firstname,lastname,profile_id,profile_type')->get()]);
    }

    /**
     * Update a class
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Classe $class)
    {
        $this->authorize('update', $class);

        $data = request()->validate([
            'label' => ['required', 'string', 'max:255'],
            'chef' => ['required', 'numeric', 'min:1'],
            'image' => ['image', 'max:1012']
        ]);

        if (!empty($data['image']))
        {
            if ($class->image != '/img/class-cover.jpg')
                Storage::delete('public/'.Str::substr($class->image, 9, Str::of($class->image)->length() - 9));
            $image = ['image' => '/storage/'.($data['image']->store('uploads/class', 'public'))];
        }

        $class->update(array_merge($data, $image ?? []));
        
        return redirect()->route('classes.discussions', ['class' => $class]);
    }

    /**
     * Show a class members (Professors and Student's)
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function members($class)
    {
        return view('class.members', 
        ['class' => Classe::with(['professors.user:id,firstname,lastname,image,status,profile_id,profile_type', 
        'students.user:id,firstname,lastname,image,status,profile_id,profile_type'])->findOrFail($class), 'tab_index' => 1]);
    }

    /**
     * Add professors to a class
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function add_professors()
    {
        $data = request()->validate([
            'class' => 'required',
            'professors' => ['required', 'array']
        ]);
        $class = Classe::find($data['class']);
        $this->authorize('add_professor', $class);
        $class->professors()->syncWithoutDetaching($data['professors']);
        return redirect()->back();
    }

    /**
     * Kick professors from a class
     *
     * @param  array  $data
     * @return \App\Groupe
     */
    public function kick_professor($class, $professor)
    {
        $c = Classe::find($class);
        $this->authorize('add_professor', $c);
        /*$data = request()->validate([
            'class' => 'required',
            'professor' => 'required'
        ]);*/
        $c->professors()->detach(/*$data['professor']*/$professor);
        return redirect()->back();
    }

    /**
     * Delete a class
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function destroy(Classe $class)
    {
        $this->authorize('delete', $class);

        global $users;
        $users = collect([]);

        $class->students->each(function ($student, $key) {
            global $users;
            $users->push($student->user_id);
        });

        User::destroy($users);
        $class->delete();
        
        return back();
    }
}
