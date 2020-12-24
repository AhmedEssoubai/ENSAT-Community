@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black"><strong>{{ $class->label }}</strong> groups</h3>
                <p class="text-muted">
                    There are {{ $class->groups->count() }} group
                </p>
            </div>
            @if (Auth::user()->isProfessor())
            <h6>
                <button class="rb rb-primary rbl" data-toggle="modal" data-target="#new_group">NEW Group</button>
            </h6>
            @endif
        </div>
        <div class="row mb-5">
            @foreach ($class->groups as $group)
            <div class="col-sm-6 col-md-4 my-3 d-flex align-content-stretch">
                <div class="course-card m-1 flex-grow-1">
                    <div class="p-4 w-100">
                        <div class="d-flex justify-content-between">
                            <h5 class="lead mb-4 text-dark">{{ $group->label }}</h5>
                            @can('delete', $group)
                                <div class="d-flex ml-2 mt-1 dropdown">
                                    <div class="text-mgray icon-hidden" id="group_{{ $group->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></div>
                                    <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="group_{{ $group->id }}_options">
                                        <a class="dropdown-item rkm-dropdown-item" href="{{ route('groups.edit', $group->id) }}">Edit</a>
                                        <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $group->id }}" href="">Delete</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                        <div class="d-flex">
                            @foreach ($group->students as $student)
                                <div class="avatar-30 mr-2">
                                    <img class="img-fluid rounded-circle" src="{{ $student->user->image }}" alt="student_img" title="{{ $student->user->firstname }} {{ $student->user->lastname }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if ($class->groups->count() == 0)
                <div class="col text-muted text-center py-2 px-2 my-3">
                    <h2 class="my-3" style="font-size: 3em"><i class="fas fa-users-slash"></i></h2>
                    <h4 class="my-3">The class has no groups</h4>
                    @if (Auth::user()->isProfessor())
                        <h5 class="my-3">Click on "NEW GROUP" to create the first group</h5>
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    @can('create', App\Group::class)
    <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
        <div class="modal-dialog rkm-dialog-message" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container h-100">
                        <h4 class="text-center text-black">Delete the group</h4>
                        <div class="text-center text-mgray mb-4">Deleting the group will cause losing all its assignments submessions. Are you sure of this?</div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                            <a type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/groups/d/')">Delete</a>
                        </div>
                        <input id="d-item-id" type="hidden" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade rkm-model" id="new_group" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
        <div class="modal-dialog rkm-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 right-corner">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container h-100">
                        <div class="row justify-content-md-center h-100 align-items-center">
                            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('groups') }}">
                                @csrf
                                <h2 class="mb-5 text-center">New group</h2>
                                <div class="form-group my-3">
                                    <input id="group_label" type="text" name="label" maxlength="64" class="rkm-form-control my-2 @error('label') is-invalid @enderror" value="{{ old('label') }}" placeholder="Label" onInput="selected_changed(selected.length)" required />
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <div id="selected_list" class="d-flex flex-wrap">
                                        <input id="search_input" class="free lead flex-grow-1 my-2" maxlength="45" placeholder="Search for students..."/>
                                    </div>
                                    <div class="border-bottom my-4"></div>
                                    <div style="height: 25vh" class="overflow-auto">
                                        <div id="empty_list" class="p-3 align-items-center d-none">
                                            <h6 class="text-muted"><span class="mr-3 lead"><i class="fas fa-user-slash"></i></span>Oops! no students found</h6>
                                        </div>
                                        <div id="results_list" class="py-3 container"></div>
                                    </div>
                                    @error('students')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="class" value="{{ $class->id }}"/>
                                <div class="form-groupe mt-4">
                                    <button id="submit_form" type="submit" class="rb rb-primary rbl w-100" disabled>Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</section>
@endsection

@push('scripts')
    @can('create', App\Group::class)
        <script src="{{ asset('js/members-scripts.js') }}"></script>
        <script>
            src = "/search/students/";
            selected_name = "students";
            selected_changed = function(count) {
                document.getElementById("submit_form").disabled = count == 0 || isEmpty(document.getElementById("group_label").value);
            };
        </script>
        <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
    @endcan
@endpush