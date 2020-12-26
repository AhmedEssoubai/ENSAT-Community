@extends('layouts.app')

@section('content')
<section id="content" class="pt-3 pb-5 text-left mx-auto bg-white page-spacing">
    <div class="container pt-3">
        <div class="row">
            <div class="col-md-9 p-0">
                <div class="post">
                    <div class="mr-3 avatar-40">
                        <img src="{{ $resource->course->professor->user->image }}" alt="profile image" class="img-fluid rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <strong class="text-dgray my-0 mr-2">{{ $resource->course->professor->user->firstname }} {{ $resource->course->professor->user->lastname }}</strong>
                            <strong class="text-mgray mr-2"> • </strong>
                            <small class="text-mgray">{{ $resource->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-3">
                            <h4 class="text-black mb-3">{{ $resource->title }}</h4>
                            <h6 class="text-black mb-3">
                                <a href="{{ route('classes.resources', $class->id) }}?filter={{ $resource->course->id }}" class="mr-2 link-animation _link text-dgray"><strong>{{ $resource->course->title }}</strong></a>
                            </h6>
                            <p id="post_content" class="text-mgray mb-3">{{ $resource->content }}</p>
                            {{-- Attachments --}}
                            @if ($resource->files->count() > 0)
                                <div class="mt-4 d-flex flex-wrap">
                                    @foreach($resource->files as $file)
                                        <a href="{{ route('files.resource', $file->id) }}" target="_blank" class="btn btn-os mr-4 my-2"><i class="fas fa-file-download mr-2"></i> {{ $file->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            @can('delete', $resource)
                                <div class="d-flex ml-2 align-items-center dropdown">
                                    <small class="text-mgray icon-hidden" id="resource_{{ $resource->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                    <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="resource_{{ $resource->id }}_options">
                                        @can('update', $resource)
                                            <a class="dropdown-item rkm-dropdown-item" href="{{ route('resources.edit', $resource->id) }}">Edit</a>
                                        @endcan
                                        <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $resource->id }}" href="">Delete</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
                @can('delete', $resource)
                <div class="mt-3">
                    <div id="view-history" class="py-3 lead text-dgray post cursor-pointer">
                        Students views history
                    </div>
                    <div class="text-dgray border-top" style="display: none">
                        <div class="loading text-center text-dgray py-3">
                            <div class="spinner-border" role="status">
                              <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="history-list" style="display: none"></div>
                    </div>
                </div>
                @endcan
            </div>
            {{-- Side --}}
            <div class="col-3 d-none d-lg-block">
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
            @can('delete', $resource)
            <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                <div class="modal-dialog rkm-dialog-message" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container h-100">
                                <h4 class="text-center text-black">Delete the resource</h4>
                                <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                    <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/resources/d/')">Delete</button>
                                </div>
                                <input id="d-item-id" type="hidden" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
</section>
@endsection

@push('scripts')
    @can('delete', $resource)
        <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/views-history-scripts.js") }}"></script>
        <script type="text/javascript">
            item = {{ $resource->id }};
            files = [
                @foreach ($resource->files as $file)
                    '{{ $file->name }}', 
                @endforeach
            ];
        </script>
    @endcan
    <script type="text/javascript">
        bringFullLifeToLinks("post_content");
    </script>
@endpush