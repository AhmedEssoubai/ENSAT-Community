@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">My classes</h3>
                <p class="text-muted">
                    You have {{ $my_classes->count() }} class
                </p>
            </div>
        </div>
        <div class="row mb-5">
            @foreach ($my_classes as $c)
            <div class="col-sm-6 col-md-4 col-lg-4 my-3">
                <div class="class-card card">
                    <div class="card-img-top img_box"><a href="{{ route('classes.discussions', $c->id) }}"><img class="img_self" src="{{ $c->image }}" alt="class image"></a></div>
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            <a class="link-dark" href="{{ route('classes.discussions', $c->id) }}">{{ $c->label }}</a>
                            @can('update', $c)
                            <div class="d-flex ml-2 align-items-center dropdown">
                                <small class="text-mgray icon-hidden" id="class_{{ $c->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="class_{{ $c->id }}_options">
                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('classes.edit', $c->id) }}">Edit</a>
                                </div>
                            </div>
                            @endcan
                        </h5>
                        <div class="content-hidden my-3 d-flex text-lgray">
                            <div class="mr-4" title="Students">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-user-graduate mr-1"></i> {{ $c->students_count }}</span>
                            </div>
                            <div class="mr-4" title="Resources">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-book mr-1"></i> {{ $c->resources_count }}</span>
                            </div>
                            <div class="mr-4" title="Assignments">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-file-alt mr-1"></i> {{ $c->assignments_count }}</span>
                            </div>
                        </div>
                        {{--<div class="d-flex">
                            <div class="flex-grow-1 d-flex">
                                <div class="avatar-30 mr-2">
                                    <img class="img-fluid rounded-circle" src="/img/avatar-0.png" alt="class image">
                                </div>
                                <div class="avatar-30 mr-2">
                                    <img class="img-fluid rounded-circle" src="/img/avatar-2.jpg" alt="class image">
                                </div>
                                <div class="avatar-30 mr-2">
                                    <img class="img-fluid rounded-circle" src="/img/avatar-2.jpg" alt="class image">
                                </div>
                                <div class="rounded-box">
                                    +4
                                </div>
                            </div>
                            <div class="d-flex pr-2 dropdown align-self-center">
                                <small class="icon-hidden" id="class_{{ $c->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></small>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="class_{{ $c->id }}_options">
                                    <a class="dropdown-item" href="#">Administrateur</a>
                                </div>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
            @endforeach
            @if ($my_classes->count() == 0)
                <div class="col text-muted text-center py-2 px-2 my-3">
                    <h2 class="my-3" style="font-size: 3em"><i class="fas fa-box-open"></i></h2>
                    <h4 class="my-3">No class to display</h4>
                </div>
            @endif
        </div>
        @can('create', App\Classe::class)
            <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
                <div>
                    <h3 class="text-black">All classes</h3>
                    <p class="text-muted">
                        The platform have {{ $all_classes->count() }} class
                    </p>
                </div>
                <h6>
                    <button class="rb rb-primary rbl" data-toggle="modal" data-target="#new_class" {{--onclick="selectLoad('select_chef', '/professors', '-')"--}}>new class</button>
                </h6>
            </div>
            <div class="row mb-5">
                @foreach ($all_classes as $c)
                <div class="col-sm-6 col-md-4 col-lg-4 my-3">
                    <div class="class-card card">
                        <div class="card-img-top img_box"><a href="{{ route('classes.discussions', $c->id) }}"><img class="img_self" src="{{ $c->image }}" alt="class image"></a></div>
                        <div class="card-body">
                            <h5 class="card-title d-flex justify-content-between">
                                <a class="link-dark" href="{{ route('classes.discussions', $c->id) }}">{{ $c->label }}</a>
                                <div class="d-flex ml-2 align-items-center dropdown">
                                    <small class="text-mgray icon-hidden" id="class_{{ $c->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                    <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="class_{{ $c->id }}_options">
                                        <a class="dropdown-item rkm-dropdown-item" href="{{ route('classes.edit', $c->id) }}">Edit</a>
                                        <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $c->id }}" href="">Delete</a>
                                    </div>
                                </div>
                            </h5>
                            <div class="content-hidden my-3 d-flex text-lgray">
                                <div class="mr-4" title="Students">
                                    <span class="content-hidden-item align-items-center"><i class="fas fa-user-graduate mr-1"></i> {{ $c->students_count }}</span>
                                </div>
                                <div class="mr-4" title="Resources">
                                    <span class="content-hidden-item align-items-center"><i class="fas fa-book mr-1"></i> {{ $c->resources_count }}</span>
                                </div>
                                <div class="mr-4" title="Assignments">
                                    <span class="content-hidden-item align-items-center"><i class="fas fa-file-alt mr-1"></i> {{ $c->assignments_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if ($all_classes->count() == 0)
                    <div class="col text-muted text-center py-2 px-2 my-3">
                        <h2 class="my-3" style="font-size: 3em"><i class="fas fa-box-open"></i></h2>
                        <h4 class="my-3">No class to display</h4>
                    </div>
                @endif
            </div>
            @endcan
    </div>
    @can('create', App\Classe::class)
    <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
        <div class="modal-dialog rkm-dialog-message" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container h-100">
                        <h4 class="text-center text-black">Delete the class</h4>
                        <div class="text-center text-mgray mb-4">Deleting the class will cause losing all its courses, students, assignments, resources, and discussions. Are you sure of this?</div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                            <a type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/classes/d/')">Delete</a>
                        </div>
                        <input id="d-item-id" type="hidden" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade rkm-model" id="new_class" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
        <div class="modal-dialog rkm-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 right-corner">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <section class="modal-body">
                    <div class="container h-100">
                        <div class="row justify-content-md-center h-100 align-items-center">
                            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('classes') }}">
                                @csrf
                                <h2 class="mb-5 text-center text-black">
                                    New class
                                </h2>
                                <div class="form-groupe mt-2 mb-4">
                                    <label for="label" class="rkm-control-label">Label</label>
                                    <input id="label" name="label" type="text" class="rkm-form-control @error('label') is-invalid @enderror" value="{{ old('label') }}" placeholder="Enter Label" required autocomplete="label" autofocus>
                                    @error('label')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group my-4">
                                    <label for="chef" class="rkm-control-label">Class Cheff</label>
                                    <select id="chef" name="chef" class="custom-select rkm-form-control @error('chef') is-invalid @enderror" required>
                                        <option disabled @empty(old('chef')) selected @endempty value>-- Select class chef --</option>
                                        @foreach ($professors as $professor)
                                            <option @if(old('chef') == '{{$professor->id}}') selected @endif value="{{$professor->id}}">{{$professor->user->firstname}} {{$professor->user->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--<div id="select_chef" class="form-group filter-select my-4 @error('chef') is-invalid @enderror">
                                    <input type="text" name="chef" required hidden>
                                    <div class="selected-options">
                                        <input class="free flex-grow-1 search-input" maxlength="45" onfocus="selectStartSearch('select_chef')" onfocusout="selectEndSearch('select_chef')" onkeyup="selectSearch(event, 'select_chef')"/>
                                    </div>
                                    <div class="options-list"></div>
                                </div>--}}
                                <div class="form-groupe mt-5">
                                    <button type="submit" class="rb rb-primary rbl w-100">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    @endcan
</section>
@endsection

@push('scripts')
    {{--<script type="text/javascript" src="{{ asset('js/select-scripts.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
@endpush