@extends('layouts.class')

@section('content-2')
<div class="container px-0">
    <div class="row">
        <div class="col-lg-9">
            {{-- Filters --}}
            <div class="d-flex justify-content-between">
                <form id="sf_form" method="GET" action="{{ route('classes.assignments', $class->id) }}" class="mb-5 px-4 d-flex">
                    <div class="mr-4">
                        <select id="filter_1" name="filter_1" class="custom-select rkm-select my-2" onchange='submitForm("sf_form")'>
                            <option @if($filter_1 == 0) selected disabled @endif value="0">Latest</option>
                            <option @if($filter_1 == 1) selected @endif value="1">@if (Auth::user()->isProfessor()) Closed @else Missing @endif</option>
                            <option @if($filter_1 == 2) selected @endif value="2">@if (Auth::user()->isProfessor()) All Submitted @else Submitted @endif</option>
                            <option @if($filter_1 == 3) selected @endif value="3">Near</option>
                        </select>
                    </div>
                    <div>
                        <select id="filter_2" name="filter_2" class="custom-select rkm-select my-2" onchange='submitForm("sf_form")'>
                            <option @if($filter_2 == 0) selected disabled @endif value="0">All</option>
                            @foreach ($class->courses as $c)
                                <option @if($filter_2 == $c->id) selected @endif value="{{$c->id}}">{{ Str::title($c->short_title) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="mb-5">
                    <form id="s_form" method="GET" action="{{ route('classes.assignments', $class->id) }}" class="rkm-form-input d-flex align-items-center py-2 px-3">
                        <span><i class="fas fa-search mr-3"></i></span>
                        <input type="text" name="search" maxlength="125" class="free py-1" placeholder="Looking For What?" onkeyup="submitFormOnEnter(event, 's_form')" value="{{ $search }}" />
                    </form>
                </div>
            </div>
            {{-- Assignments --}}
            <div class="posts-list">
                @if ($assignments->count())
                    @foreach($assignments as $assignment)
                        <div id="p_{{ $assignment->id}}" class="posts-list-item d-flex
                            @switch($assignment->getStatus(Auth::user()))
                                @case('submitted')
                                    posts-list-item-success
                                    @break
                                @case('closed')
                                    posts-list-item-danger
                                    @break
                                @case('near')
                                    posts-list-item-warning
                                    @break
                            @endswitch"  {{--onclick="clickLink('post_show', 'p_{{ $assignment->id}}')"--}}>
                            <div class="d-flex align-items-center mr-4">
                                <img src="{{ $assignment->professor->user->image }}" alt="profile image" class="avatar-60 rounded-circle" title="{{ $assignment->professor->user->firstname }} {{ $assignment->professor->user->lastname }}"/>
                            </div>
                            <div class="mr-2 w-100">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="d-flex">
                                        <a href="{{ route('classes.assignments', $class->id) }}?filter_2={{ $assignment->course->id }}" class="mr-2 _link text-dgray text-up"><strong>{{ $assignment->course->short_title }}</strong></a>
                                        <span class="text-lgray">• {{ Str::upper($assignment->created_at->diffForHumans()) }} </span>
                                    </div>
                                    @if ($assignment->getStatus(Auth::user()) == 'submitted')
                                        <div class="tag tag-success text-up text-bold">submitted</div>
                                    @endif
                                </div>
                                <h4 class="mb-4 text-bold-600"><a id="post_show" href="{{ route('assignments.show', $assignment->id) }}" class="text-dark line-clamp">{{ $assignment->title }}</a></h4>
                                <p id="post_content_{{ $loop->index }}" class="text-dgray mb-2 line-clamp lc-3">{{ $assignment->objectif }}</p>
                                <div class="d-flex">
                                    <span class="mr-3 text-red">Deadline: {{ $assignment->deadline->format('h:i A') }} (@if (!$assignment->is_closed()) {{ $assignment->deadline->diffForHumans([
                                        'syntax' => Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                        'options' => Carbon\Carbon::JUST_NOW | Carbon\Carbon::ONE_DAY_WORDS | Carbon\Carbon::TWO_DAY_WORDS,
                                    ]) }}
                                    @else
                                    closed
                                    @endif)
                                    </span>
                                    @can('delete', $assignment)
                                        <div class="d-flex align-items-center dropdown">
                                            <span class="text-mgray icon-hidden" id="assignment_{{ $assignment->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></span>
                                            <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="assignment_{{ $assignment->id }}_options">
                                                {{--@can('update', $assignment)
                                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('assignments.edit', $assignment->id) }}">Edit</a>
                                                @endcan--}}
                                                <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_post" data-id="{{ $assignment->id }}" href="">Delete</a>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted text-center py-5 px-2">
                        <h2 class="my-3" style="font-size: 3em"><i class="far fa-clipboard"></i></h2>
                        <h4 class="my-3">There is no assignment to display</h4>
                        <h5 class="my-3">
                            @if($filter_2 == 0) 
                                The class 
                            @else
                                The course <strong>"{{ Str::title($class->courses->find($filter_2)->short_title) }}"</strong> 
                            @endif
                            has no assignments @isset($search) related to <strong>"{{ $search }}"</strong> @endisset @if($filter_1 != 0) with the used filters @endif</h5>
                    </div>
                @endif
            </div>
            <div class="mt-3">
                {{ $assignments->links() }}
            </div>
        </div>
        {{-- Side --}}
        <div class="col-3 d-none d-lg-block">
            @can('create', App\Assignment::class)
            <div class="mb-5 mx-3">
                <button class="rb rb-primary rbl w-100" data-toggle="modal" data-target="#new_assignment">new assignment</button>
            </div>
            @endcan
            <x-side-bar :students="$students" :class="$class" :twassignments="$tw_assignments" :nwassignments="$nw_assignments"/>
        </div>
        @if (Auth::user()->isProfessor())
        <div class="modal fade rkm-model" id="delete_post" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
            <div class="modal-dialog rkm-dialog-message" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container h-100">
                            <h4 class="text-center text-black">Delete the assignment</h4>
                            <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="deletePost('assignment')">Delete</button>
                            </div>
                            <input id="d-post-id" type="hidden" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade rkm-model" id="new_assignment" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
            <div class="modal-dialog rkm-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 right-corner">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container h-100 py-5">
                            <div class="row justify-content-md-center h-100 align-items-center pt-3">
                                <form class="col-sm-12 col-md-8 col-lg-6" method="POST" enctype="multipart/form-data" action="{{ route('assignments') }}">
                                    @csrf
                                    <div class="mb-5 d-flex justify-content-between align-items-center">
                                        <h2 class="text-center text-black">New assignment</h2>
                                        <button type="submit" class="rb rb-primary rbl">Publish</button>
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="title" class="rkm-control-label">Title</label>
                                        <input id="title" type="text" name="title" maxlength="125" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Title" required />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="objectif" class="rkm-control-label">Objectif</label>
                                        <textarea id="objectif" class="rkm-form-control @error('objectif') is-invalid @enderror" name="objectif" rows="4" placeholder="Enter Objectif" required>{{ old('objectif') }}</textarea>
                                        @error('objectif')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="course" class="rkm-control-label">Course</label>
                                        <select id="course" name="course" class="custom-select rkm-form-control @error('course') is-invalid @enderror" required>
                                            <option disabled @empty(old('course')) selected @endif value>-- Select assignment course --</option>
                                            @foreach ($prof_courses as $course)
                                                <option @if(old('course') == '{{$course->id}}') selected @endif value="{{$course->id}}">{{$course->title}}</option>
                                            @endforeach
                                        </select>
                                        @error('course')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-row align-items-center">
                                        <div class="col-7 my-3">
                                            <label for="deadline" class="rkm-control-label">Deadline</label>
                                            <input id="deadline" type="datetime-local" name="deadline" class="rkm-form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}" placeholder="Deadline" required />
                                            @error('deadline')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-5 my-3">
                                            <label for="assigned_type" class="rkm-control-label">Target</label>
                                            <select id="assigned_type" name="assigned_type" class="custom-select rkm-form-control @error('assigned_type') is-invalid @enderror" required>
                                                <option @if(old('assigned_type') == 0) selected @endif value="0">Individuals</option>
                                                <option @if(old('assigned_type') == 1) selected @endif value="1">Groups</option>
                                            </select>
                                            @error('assigned_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group my-3" style="border-bottom: 1px solid rgba(114, 114, 114, 0.5)">
                                        <label for="assigned-btn" class="rkm-control-label">Target</label>
                                        <button id="assigned-btn" type="button" class="btn-free w-100 text-left text-dgray lead mb-2 @error('targets') is-invalid @enderror">
                                            All students
                                        </button>
                                        <div id="assigned-list" style="display: none; border-top: 1px solid rgba(114, 114, 114, 0.2)">
                                            <div class="custom-control custom-checkbox my-3">
                                                <input type="checkbox" class="custom-control-input @error('assigned_all') is-invalid @enderror" id="opt-0" name="assigned_all" value="1" checked>
                                                <label class="custom-control-label w-100" for="opt-0">All students</label>
                                                @error('assigned_all')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                <div id="target-list"></div>
                                            </div>
                                        </div>
                                        @error('targets')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" onclick="create_attachment('attachments_list')" class="rbo-secondary mt-3 pl-3"><span class="mr-2"><i class="fas fa-paperclip"></i></span> Add attachment</button>
                                        <div id="attachments_list" class="my-4">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    @if (Auth::user()->isProfessor())
        <script>
            var students_ids = [
                @foreach ($students_ids as $id)
                    {{$id}}, 
                @endforeach
            ];
            var students_names = [
                @foreach ($students_names as $name)
                    '{{$name}}', 
                @endforeach
            ];
            var groups_ids = [
                @foreach ($groups_ids as $id)
                    {{$id}}, 
                @endforeach
            ];
            var groups_names = [
                @foreach ($groups_names as $name)
                    '{{$name}}', 
                @endforeach
            ];
        </script>
        <script type="text/javascript" src="{{ asset("js/files-scripts.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/assignment-form.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/post-scripts.js") }}"></script>
    @endif
    <script type="text/javascript">
        for(i = 0; i < {{ $assignments->count() }}; i++)
            bringLifeToLinks("post_content_" + i);
    </script>
@endpush