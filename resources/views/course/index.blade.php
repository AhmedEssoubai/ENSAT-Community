@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">Courses</h3>
                <p class="text-muted">
                    You have {{ $courses->count() }} course
                </p>
            </div>
            @can ('create', App\Course::class)
                <h6>
                    <button class="rb rb-primary rbl" data-toggle="modal" data-target="#new_cours">New Course</button>
                </h6>
            @endcan
        </div>
        <div class="row mb-5">
            @if ($courses->count())
                @foreach ($courses as $course)
                <div class="col-md-6 col-lg-4 my-3 d-flex align-content-stretch">
                    <div class="course-card big mx-1 px-0 container">
                        <div class="row w-100">
                            <div class="col-3 card-border container p-3 text-white py-5" style="background-color: {{ $course->color }}">
                                <div class="row mb-4 justify-content-center" title="Resources">
                                    <span class="align-items-center"><i class="fas fa-book mr-1"></i> {{ $course->resources_count }}</span>
                                </div>
                                <div class="row justify-content-center" title="Assignments">
                                    <span class="align-items-center"><i class="fas fa-file-alt mr-1"></i> {{ $course->assignments_count }}</span>
                                </div>
                            </div>
                            <div class="col-9 p-4">
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="lead">
                                        <a href="{{ route('classes.discussions', $class->id) }}?filter_2={{ $course->id }}" class="text-dark line-clamp" title="{{ $course->title }}">{{ $course->title }}</a>
                                    </h5>
                                    @can('delete', $course)
                                        <div class="d-flex ml-2 dropdown">
                                            <div class="text-mgray icon-hidden" id="course_{{ $course->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="course_{{ $course->id }}_options">
                                                @can('update', $course)
                                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('courses.edit', $course->id) }}">Edit</a>
                                                @endcan
                                                <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $course->id }}" href="">Delete</a>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                                <p class="small text-muted mb-4 line-clamp lc-4">
                                    {{ $course->description }}
                                </p>
                                <p class="text-muted">
                                    {{ $course->professor->user->firstname }} {{ $course->professor->user->lastname }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col text-muted text-center py-2 px-2 my-3">
                    <h2 class="my-3" style="font-size: 3em"><i class="fas fa-box-open"></i></h2>
                    <h4 class="my-3">No course to display</h4>
                </div>
            @endif
        </div>
    </div>
    @if (Auth::user()->isProfessor())
    <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
        <div class="modal-dialog rkm-dialog-message" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container h-100">
                        <h4 class="text-center text-black">Delete the course</h4>
                        <div class="text-center text-mgray mb-4">Deleting the course will cause losing all its assignments and resources. Are you sure of this?</div>
                        <div class="d-flex justify-content-center mt-2">
                            <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                            <a type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/courses/d/')">Delete</a>
                        </div>
                        <input id="d-item-id" type="hidden" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade rkm-model" id="new_cours" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
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
                            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" enctype="multipart/form-data" action="{{ route('courses') }}">
                                @csrf
                                <h2 class="mb-5 text-center text-black">New cours</h2>
                                <div class="form-group my-3">
                                    <label for="title" class="rkm-control-label">Title</label>
                                    <input id="title" type="text" name="title" maxlength="125" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter Title" required />
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label for="short_title" class="rkm-control-label">Short Title</label>
                                    <input id="short_title" type="text" name="short_title" maxlength="10" class="rkm-form-control @error('short_title') is-invalid @enderror" value="{{ old('short_title') }}" placeholder="Enter Short Title" required />
                                    @error('short_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label for="color" class="rkm-control-label">Color</label>
                                    <input id="color" type="color" name="color" class="rkm-form-control @error('color') is-invalid @enderror" value="{{ old('color', '#4e89da') }}" placeholder="Enter Short Title" required />
                                    @error('color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group my-3">
                                    <label for="description" class="rkm-control-label">Description</label>
                                    <textarea id="description" class="rkm-form-control @error('description') is-invalid @enderror" name="description" rows="3" cols="30" maxlength="255" placeholder="Enter Description" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <input type="hidden" name="class_id" value="{{ $class->id }}" />
                                <div class="form-groupe mt-4">
                                    <button type="submit" class="rb rb-primary rbl w-100">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
@endpush