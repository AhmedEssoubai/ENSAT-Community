@extends('layouts.app')

@section('content')
<section id="content" class="pt-3 pb-5 text-left mx-auto bg-white page-spacing">
    <div class="container pt-3">
        <div class="row">
            <div class="col-md-9 mx-md-auto p-0">
                <div class="post">
                    <div class="mr-3 avatar-40">
                        <img src="{{ $announcement->professor->user->image }}" alt="profile image" class="img-fluid rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center">
                            <strong class="text-dgray my-0 mr-2">{{ $announcement->professor->user->firstname }} {{ $announcement->professor->user->lastname }}</strong>
                            <strong class="text-mgray mr-2"> • </strong>
                            <small class="text-mgray">{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="mt-3">
                            <h4 class="text-black mb-3">{{ $announcement->title }}</h4>
                            <h6 class="text-dgray mb-3">
                                for 
                                @foreach ($announcement->classes as $c)
                                    {{ $c->label }}@if (!$loop->last), @endif
                                @endforeach
                            </h6>
                            <p id="post_content" class="text-mgray mb-3">{{ $announcement->content }}</p>
                            {{-- Attachments --}}
                            {{--@if ($announcement->files->count() > 0)
                                <div class="mt-4 d-flex flex-wrap">
                                    @foreach($announcement->files as $file)
                                        <a href="{{ route('files.resource', $file->id) }}" target="_blank" class="btn btn-os mr-4 my-2"><i class="fas fa-file-download mr-2"></i> {{ $file->name }}</a>
                                    @endforeach
                                </div>
                            @endif--}}
                            @can('delete', $announcement)
                                <div class="d-flex ml-2 align-items-center dropdown">
                                    <small class="text-mgray icon-hidden" id="announcement_{{ $announcement->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                    <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="announcement_{{ $announcement->id }}_options">
                                        @can('update', $announcement)
                                            <a class="dropdown-item rkm-dropdown-item" href="{{ route('announcements.edit', $announcement->id) }}">Edit</a>
                                        @endcan
                                        <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $announcement->id }}" href="">Delete</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            @can('delete', $announcement)
            <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                <div class="modal-dialog rkm-dialog-message" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container h-100">
                                <h4 class="text-center text-black">Delete the announcement</h4>
                                <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                    <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/announcements/d/')">Delete</button>
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
    @can('delete', $announcement)
        <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
    @endcan
    <script type="text/javascript">
        bringFullLifeToLinks("post_content");
    </script>
@endpush