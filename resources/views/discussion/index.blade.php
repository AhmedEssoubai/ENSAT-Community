@extends('layouts.class')

@section('content-2')
<div class="container px-0">
    <div class="row">
        <div class="col-lg-9">
            {{-- Filters --}}
            <div class="d-flex justify-content-between">
                <form id="sf_form" method="GET" action="{{ route('classes.discussions', $class->id) }}" class="mb-5 px-4 d-flex">
                    <div class="mr-4">
                        <select id="filter_1" name="filter_1" class="custom-select rkm-select my-2" onchange='submitForm("sf_form")'>
                            <option @if($filter_1 == 0) selected disabled @endif value="0">Latest</option>
                            <option @if($filter_1 == 1) selected @endif value="1">Popular</option>
                            <option @if($filter_1 == 2) selected @endif value="2">No Replies Yet</option>
                        </select>
                    </div>
                    <div>
                        <select id="filter_2" name="filter_2" class="custom-select rkm-select my-2" onchange='submitForm("sf_form")'>
                            <option @if($filter_2 == -1) selected disabled @endif value="-1">All</option>
                            <option @if($filter_2 == 0) selected @endif value="0">General</option>
                            @foreach ($class->courses as $c)
                                <option @if($filter_2 == $c->id) selected @endif value="{{$c->id}}">{{ Str::title($c->short_title) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @isset($search)
                        <input type="hidden" name="search" value="{{ $search }}"/>
                    @endisset
                </form>
                <div class="mb-5">
                    <form id="s_form" method="GET" action="{{ route('classes.discussions', $class->id) }}" class="rkm-form-input d-flex align-items-center py-2 px-3">
                        <span><i class="fas fa-search mr-3"></i></span>
                        <input type="text" name="search" maxlength="125" class="free py-1" placeholder="Looking For What?" onkeyup="submitFormOnEnter(event, 's_form')" value="{{ $search }}" />
                        @isset($filter_1)
                            <input type="hidden" name="filter_1" value="{{ $filter_1 }}"/>
                            <input type="hidden" name="filter_2" value="{{ $filter_2 }}"/>
                        @endisset
                    </form>
                </div>
            </div>
            {{-- Discussions --}}
            <div class="posts-list">
                @if ($discussions->count())
                    @foreach($discussions as $discussion)
                        <div id="p_{{ $discussion->id}}" class="posts-list-item d-flex" {{--onclick="clickLink('post_show', 'p_{{ $discussion->id}}')"--}}>
                            <div class="mt-2 mr-4">
                                <img src="{{ $discussion->user->image }}" alt="profile image" class="avatar rounded-circle" title="{{ $discussion->user->firstname }} {{ $discussion->user->lastname }}"/>
                            </div>
                            <div class="mr-5 flex-fill">
                                <h5 class="text-bold-600"><a id="post_show" href="{{ route('discussions.show', $discussion->id) }}" class="text-dark line-clamp">{{ $discussion->title }}</a></h5>
                                <p id="post_content_{{ $loop->index }}" class="text-dgray mb-2 line-clamp lc-2">{{ $discussion->content }}</p>
                                <div class="d-flex">
                                    <small class="text-mgray">posted {{ $discussion->created_at->diffForHumans() }}</small>
                                    @can('delete', $discussion)
                                        <div class="d-flex ml-2 align-items-center dropdown">
                                            <small class="text-mgray icon-hidden" id="discussion_{{ $loop->index }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                            <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="discussion_{{ $loop->index }}_options">
                                                @can('update', $discussion)
                                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('discussions.edit', $discussion->id) }}">Edit</a>
                                                @endcan
                                                <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_post" data-id="{{ $discussion->id }}" href="">Delete</a>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="ml-1 d-flex align-items-center">
                                <div class="d-flex mr-3">
                                    <span class="mr-2"><i class="fa fa-heart"></i></span>
                                    {{ $discussion->favorited_users_count }}
                                </div>
                                <div class="d-flex mr-3">
                                    <span class="mr-2"><i class="fas fa-comment"></i></span>
                                    {{ $discussion->comments_count }}
                                </div>
                                <div>
                                    @isset($discussion->course)
                                        <a class="rbo-primary" 
                                            style="border-color: {{ $discussion->course->color }}; color: {{ $discussion->course->color }}"
                                            href="{{ route('classes.discussions', $class->id) }}?filter_2={{ $discussion->course->id }}"
                                            onmouseenter="rboOnMouseOver(event, '{{ $discussion->course->color }}')" onmouseout="rboOnMouseOut(event, '{{ $discussion->course->color }}')">{{ Str::upper($discussion->course->short_title) }}</a>
                                    @else
                                        <a class="rbo-primary" href="{{ route('classes.discussions', $class->id) }}?filter_2=0">GENERAL</a>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted text-center py-5 px-2">
                        <h2 class="my-3" style="font-size: 3em"><i class="fas fa-cloud-showers-heavy"></i></h2>
                        <h4 class="my-3">
                            @isset($search) There is no discussions related to "{{ $search }}"
                            @else There is no discussions to display  @endisset
                        </h4>
                        <h5 class="my-3">If you have any questions post them here</h5>
                    </div>
                @endif
            </div>
            <div class="mt-3">
                {{ $discussions->links() }}
            </div>
        </div>
        {{-- Side --}}
        <div class="col-3 d-none d-lg-block">
            <div class="mb-5 mx-3">
                <button class="rb rb-primary rbl w-100" data-toggle="modal" data-target="#new_discussion">NEW DISCUSSION</button>
            </div>
            <x-side-bar :students="$students" :class="$class" :twassignments="$tw_assignments" :nwassignments="$nw_assignments" :ltannouncements="$lt_announcements"/>
        </div>
        <div class="modal fade rkm-model" id="delete_post" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
            <div class="modal-dialog rkm-dialog-message" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container h-100">
                            <h4 class="text-center text-black">Delete the discussion</h4>
                            <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="deletePost('discussion')">Delete</button>
                            </div>
                            <input id="d-post-id" type="hidden" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade rkm-model" id="new_discussion" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
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
                                <form class="col-sm-12 col-md-8 col-lg-6" method="POST" enctype="multipart/form-data" action="{{ route('discussions') }}">
                                    @csrf
                                    <h2 class="mb-5 text-center text-black">New discussion</h2>
                                    <div class="form-group my-3">
                                        <label for="title" class="rkm-control-label">Title</label>
                                        <input type="text" name="title" maxlength="125" minlength="6" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter Title" required />
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="content" class="rkm-control-label">Content</label>
                                        <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="3" minlength="12" placeholder="Enter Content" required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group my-3">
                                        <label for="course" class="rkm-control-label">Course</label>
                                        <select id="course" name="course" class="custom-select rkm-form-control">
                                            <option @empty(old('course')) selected @endempty value="0">General</option>
                                            @foreach ($class->courses as $c)
                                                <option @if(old('course') == '{{$c->id}}') selected @endif value="{{$c->id}}">{{ $c->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group my-3">
                                        <label class="rkm-control-label">Image</label>
                                        <div class="custom-file @error('image') is-invalid @enderror">
                                            <input id="discussionImage" type="file" name="image" accept="image/x-png,image/gif,image/jpeg" class="custom-file-input @error('image') is-invalid @enderror" value="{{ old('image') }}">
                                            <label class="custom-file-label rkm-custom-file-label" for="discussionImage">Choose image</label>
                                        </div>
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <input type="hidden" name="class" value="{{ $class->id }}"/>
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
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset("js/post-scripts.js") }}"></script>
    <script type="text/javascript">
        for(i = 0; i < {{ $discussions->count() }}; i++)
            bringLifeToLinks("post_content_" + i);
    </script>
@endpush