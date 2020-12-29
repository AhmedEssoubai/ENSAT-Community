@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">Announcements</h3>
                <p class="text-muted">
                    You have 0
                </p>
            </div>
            @can('create', App\Announcement::class)
                <div class="mb-5 mx-3">
                    <button class="rb rb-primary rbl w-100" data-toggle="modal" data-target="#new_announcement">new announcement</button>
                </div>
            @endcan
        </div>
        <div class="row mb-5 chronological-list">
            <div class="col-12 mb-3 d-flex">
                <div class="px-4 py-2 text-dgray mx-auto border-bottom border-primary lead text-bold" style="border-width: 2px !important">2020</div>
            </div>
            <div class="col-12 chronological-section">
                <div class="chronological-date">
                    <div class="d-flex">
                        <div class="chronological-date-box mr-2 text-dgray text-up" style="font-size: 0.75rem; font-weight: 700">
                            DEC
                        </div>
                        <div class="chronological-date-box text-dgray text-up">
                            20
                        </div>
                    </div>
                    <div class="text-dgray text-right">
                        <small>20 hours ago</small>
                    </div>
                </div>
                <div class="chronological-contents">
                    @foreach ($day_announcements as $announcement)
                        <div class="chronological-contents-item">
                            <div class="chronological-contents-type">
                                <div class="chronological-contents-type-icon text-dgray"><i class="fas fa-bullhorn"></i></div>
                            </div>
                            <h4><a class="_link" href="">{{ $announcement->title }}</a></h4>
                            <div class="mt-2 mb-3 text-dark">by {{ $announcement->professor->user->firstname }} {{ $announcement->professor->user->lastname }}</div>
                            <div class="text-dgray" id="post_content_{{ $announcement->id }}">
                                {{ $announcement->content }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- <i class="fas fa-bullhorn"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-exclamation-triangle"></i>  --}}
            {{--@foreach ($my_classes as $c)
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
                    </div>
                </div>
            </div>
            @endforeach--}}
        </div>
        @can('create', App\Announcement::class)
            <div class="modal fade rkm-model" id="delete_post" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                <div class="modal-dialog rkm-dialog-message" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container h-100">
                                <h4 class="text-center text-black">Delete the announcement</h4>
                                <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                    <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="deletePost('resource')">Delete</button>
                                </div>
                                <input id="d-post-id" type="hidden" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade rkm-model" id="new_announcement" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
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
                                    <form class="col-sm-12 col-md-8 col-lg-6" method="POST" enctype="multipart/form-data" action="{{ route('resources') }}">
                                        @csrf
                                        <div class="mb-5 d-flex justify-content-between align-items-center">
                                            <h2 class="text-center">New announcement</h2>
                                        </div>
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
                                            <label for="content" class="rkm-control-label">Content</label>
                                            <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="4" maxlength="500" placeholder="Enter Content" required>{{ old('content') }}</textarea>
                                            @error('content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group my-3" style="border-bottom: 1px solid rgba(114, 114, 114, 0.5)">
                                            <label for="assigned-btn" class="rkm-control-label">Classes</label>
                                            <button id="assigned-btn" type="button" class="btn-free w-100 text-left text-dgray lead mb-2 @error('classes') is-invalid @enderror">
                                                Classes
                                            </button>
                                            <div id="assigned-list" style="display: none; border-top: 1px solid rgba(114, 114, 114, 0.2)">
                                                <div class="custom-control custom-checkbox my-3">
                                                    <input type="checkbox" class="custom-control-input" id="opt-0" checked>
                                                    <label class="custom-control-label w-100" for="opt-0">All students</label>
                                                    <div id="classes-list"></div>
                                                </div>
                                            </div>
                                            @error('classes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-groupe mt-4">
                                            <button type="submit" class="rb rb-primary rbl w-100">Publish</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</section>
@endsection

@push('scripts')
    @if (Auth::user()->isProfessor())
        <script type="text/javascript" src="{{ asset("js/announcement-form.js") }}"></script>
    @endif
@endpush