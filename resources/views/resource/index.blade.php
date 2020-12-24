@extends('layouts.class')

@section('content-2')
<div class="container px-0">
    <div class="row">
        <div class="col-lg-9">
            {{-- Filters --}}
            <div class="d-flex justify-content-between">
                <form id="sf_form" method="GET" action="{{ route('classes.resources', $class->id) }}" class="mb-5 px-4 d-flex">
                    <div>
                        <select id="filter" name="filter" class="custom-select rkm-select my-2" onchange='submitForm("sf_form")'>
                            <option @if($filter == 0) selected @endif value="0">All</option>
                            @foreach ($class->courses as $c)
                                <option @if($filter == $c->id) selected @endif value="{{$c->id}}">{{ Str::title($c->short_title) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="mb-5">
                    <form id="s_form" method="GET" action="{{ route('classes.resources', $class->id) }}" class="rkm-form-input d-flex align-items-center py-2 px-3">
                        <span><i class="fas fa-search mr-3"></i></span>
                        <input type="text" name="search" maxlength="125" class="free py-1" placeholder="Looking For What?" onkeyup="submitFormOnEnter(event, 's_form')" value="{{ $search }}" />
                    </form>
                </div>
            </div>
            {{-- Resources --}}
            <div class="posts-list">
                @if ($resources->count())
                    @foreach($resources as $resource)
                        <div id="p_{{ $resource->id}}" class="posts-list-item d-flex" {{--onclick="clickLink('post_show', 'p_{{ $resource->id}}')"--}}>
                            <div class="d-flex align-items-center mr-4">
                                <img src="{{ $resource->professor->user->image }}" alt="profile image" class="avatar-60 rounded-circle" title="{{ $resource->professor->user->firstname }} {{ $resource->professor->user->lastname }}"/>
                            </div>
                            <div class="mr-2">
                                <p class="d-flex">
                                    <a href="{{ route('classes.resources', $class->id) }}?filter={{ $resource->course->id }}" class="mr-2 link-animation _link text-dgray text-up" {{--onmouseenter="linkOnMouseOver(event, '{{ $resource->course->color }}')" onmouseout="linkOnMouseOut(event, '#606f7b')"--}}><strong>{{ $resource->course->short_title }}</strong></a>
                                    <span class="text-lgray">• {{ Str::upper($resource->created_at->diffForHumans()) }}</span>
                                </p>
                                <h4 class="mb-4 text-bold-600"><a id="post_show" href="{{ route('resources.show', $resource->id) }}" class="text-dark line-clamp">{{ $resource->title }}</a></h4>
                                <p id="post_content_{{ $loop->index }}" class="text-dgray mb-2 line-clamp lc-3">{{ $resource->content }}</p>
                                @can('delete', $resource)
                                    <div class="d-flex">
                                        <div class="d-flex align-items-center dropdown">
                                            <span class="text-mgray icon-hidden" id="resource_{{ $resource->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></span>
                                            <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="resource_{{ $resource->id }}_options">
                                                @can('update', $resource)
                                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('resources.edit', $resource->id) }}">Edit</a>
                                                @endcan
                                                <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_post" data-id="{{ $resource->id }}">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted text-center py-5 px-2">
                        <h2 class="my-3" style="font-size: 3em"><i class="fas fa-folder-open"></i></h2>
                        <h4 class="my-3">There is no resources to display</h4>
                        <h5 class="my-3">
                            @if($filter == 0) 
                                The class 
                            @else
                                The course <strong>"{{ Str::title($class->courses->find($filter_2)->short_title) }}"</strong> 
                            @endif
                            has no resources @isset($search) related to <strong>"{{ $search }}"</strong> @endisset</h5>
                    </div>
                @endif
            </div>
            <div class="mt-3">
                {{ $resources->links() }}
            </div>
        </div>
        {{-- Side --}}
        <div class="col-3 d-none d-lg-block">
            @can('create', App\Resource::class)
            <div class="mb-5 mx-3">
                <button class="rb rb-primary rbl w-100" data-toggle="modal" data-target="#new_resource">new resource</button>
            </div>
            @endcan
            <x-side-bar :students="$students" :class="$class" :twassignments="$tw_assignments" :nwassignments="$nw_assignments"/>
            {{-- Assignments list --}}
            {{--<div class="py-3 mb-4 border-rounded">
                <h6 class="text-dark mx-3 mb-3">This week assignments</h6>
                <div class="rkm-list-group">
                    <a href="#" class="list-group-item d-flex align-items-center border-0">
                        <small>Lorem ipsum dolor sit amet consectetur adipisicing elit.</small>
                    </a>
                    <a href="#" class="list-group-item d-flex align-items-center border-0">
                        <small>Non in dolores odio.</small>
                    </a>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="_link px-3"><small>Show all</small></a>
                </div>
            </div>--}}
            {{-- Annoucments list --}}
            {{--<div class="py-3 mb-4 border-rounded">
                <h6 class="text-dark mx-3 mb-3">This week annoucments</h6>
                <div class="rkm-list-group">
                    <a href="#" class="list-group-item d-flex align-items-center border-0">
                        <small>Lorem ipsum dolor sit amet consectetur adipisicing elit.</small>
                    </a>
                    <a href="#" class="list-group-item d-flex align-items-center border-0">
                        <small>Non in dolores odio.</small>
                    </a>
                </div>
                <div class="text-center mt-3">
                    <a href="#" class="_link px-3"><small>Show all</small></a>
                </div>
            </div>--}}
        </div>
        @if (Auth::user()->isProfessor())
            <div class="modal fade rkm-model" id="delete_post" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                <div class="modal-dialog rkm-dialog-message" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container h-100">
                                <h4 class="text-center text-black">Delete the resource</h4>
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
        @endif
        @can('create', App\Resource::class)
            <div class="modal fade rkm-model" id="new_resource" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
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
                                            <h2 class="text-center">New resource</h2>
                                            <button type="submit" class="rb rb-primary rbl">Publish</button>
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
                                            <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="4" placeholder="Enter Content" required>{{ old('content') }}</textarea>
                                            @error('content')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="course" class="rkm-control-label">Course</label>
                                            <select id="course" name="course" class="custom-select rkm-form-control @error('course') is-invalid @enderror" required>
                                                <option disabled @empty(old('course')) selected @endif value>-- Select resource course --</option>
                                                @foreach ($prof_courses as $course)
                                                    <option @if(old('course') == '{{ $course->id }}') selected @endif value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('course')
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
        @endcan
    </div>
</div>
@endsection

@push('scripts')
    @can('create', App\Resource::class)
        <script type="text/javascript" src="{{ asset("js/files-scripts.js") }}"></script>
    @endcan
    <script type="text/javascript" src="{{ asset("js/post-scripts.js") }}"></script>
    <script type="text/javascript">
        for(i = 0; i < {{ $resources->count() }}; i++)
            bringLifeToLinks("post_content_" + i);
    </script>
@endpush