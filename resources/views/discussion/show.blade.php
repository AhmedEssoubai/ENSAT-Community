@extends('layouts.app')

@section('content')
<section id="content" class="pt-3 pb-5 text-left mx-auto bg-white page-spacing">
    <div class="container pt-3">
        <div class="row">
            <div class="col-md-9 p-0">
                <div class="post">
                    <div class="mr-3 avatar-40">
                        <img src="{{ $discussion->user->image }}" alt="profile image" class="img-fluid rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <strong class="text-black my-0 mr-2">{{ $discussion->user->firstname }} {{ $discussion->user->lastname }}</strong>
                                <strong class="text-mgray mr-2"> • </strong>
                                <small class="text-mgray">{{ $discussion->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="d-flex align-items-center text-lgray">
                                <div class="d-flex mr-3">
                                    <span class="mr-2"><i class="fa fa-heart"></i></span>
                                    <span id="likes_{{ $discussion->id }}">{{ $discussion->favorited_users->count() }}</span>
                                </div>
                                <div class="d-flex mr-3">
                                    <span class="mr-2"><i class="fas fa-comment"></i></span>
                                    <span id="count">{{ $comments->count() }}</span>
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
                        <div class="mt-3">
                            <h4 class="text-black mb-3">{{ $discussion->title }}</h4>
                            <p id="post_content" class="text-mgray mb-3">{{ $discussion->content }}</p>
                            @if (!empty($discussion->image))
                                <img src="/storage/{{ $discussion->image }}" class="img-fluid mb-3" alt="discussion image">
                            @endif
                            <div class="d-flex">
                                <div id="fa_{{ $discussion->id }}_fav" class="icon-hidden text-mgray mr-2
                                    @if ($discussion->is_liked())
                                        active
                                    @endif"  onclick="favorite({{ $discussion->id }})">
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                </div>
                                @can('delete', $discussion)
                                    <div class="d-flex ml-2 align-items-center dropdown">
                                        <small class="text-mgray icon-hidden" id="discussion_{{ $discussion->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                        <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="discussion_{{ $discussion->id }}_options">
                                            @can('update', $discussion)
                                                <a class="dropdown-item rkm-dropdown-item" href="{{ route('discussions.edit', $discussion->id) }}">Edit</a>
                                            @endcan
                                            <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $discussion->id }}" href="">Delete</a>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="post">
                    <input type="text" id="cmt_content" class="rkm-input mr-4 flex-grow-1" placeholder="Engage with the discussion..."/>
                    <button type="button" id="btn_replay" class="rb rb-primary px-4" onclick="addComment({{ $discussion->id }}, '{{ $user->image }}', '{{ $user->firstname }}' + ' ' + '{{ $user->lastname }}')">Replay</button>
                </div>
                <div id="comments">
                    @if ($comments->count())
                        @foreach($comments as $comment)
                        <div id="c_{{ $comment->id }}" class="post">
                            <div class="mr-3 avatar-40">
                                <img src="{{ $comment->user->image }}" alt="profile image" class="img-fluid rounded-circle" />
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <strong class="text-black my-0 mr-2">{{ $comment->user->firstname }} {{ $comment->user->lastname }}</strong>
                                    <strong class="text-mgray mr-2"> • </strong>
                                    <small class="text-mgray">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="mt-3">
                                    <p class="text-mgray mb-3" id="cmt_{{ $comment->id }}_content">{{ $comment->content }}</p>
                                    @can('delete', $comment)
                                        <div class="d-flex">
                                            <div class="d-flex ml-2 align-items-center dropdown">
                                                <small class="text-mgray icon-hidden" id="cmt_{{ $comment->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                                <div class="dropdown-menu dropdown-menu rkm-dropdown-menu" aria-labelledby="cmt_{{ $comment->id }}_options">
                                                    @can('update', $comment)
                                                        <button class="dropdown-item rkm-dropdown-item" type="button" data-toggle="modal" data-target="#edit_comment" data-id="{{ $comment->id }}">Edit</button>
                                                    @endcan
                                                    <button type="button" class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_comment" data-id="{{ $comment->id }}">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="p-4">
                    <div class="modal fade rkm-model" id="edit_comment" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                        <div class="modal-dialog rkm-dialog-message" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="container h-100">
                                        <h4 class="text-center text-black">Edit a comment</h4>
                                        <div class="form-group my-5">
                                            <input id="comment-id" type="hidden" />
                                            <textarea class="rkm-form-control" cols="50" rows="1" id="comment-text"></textarea>
                                        </div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                            <button type="submit" class="rb rb-primary mx-2" data-dismiss="modal" onclick="editComment()">Save the changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade rkm-model" id="delete_comment" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                        <div class="modal-dialog rkm-dialog-message" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="container h-100">
                                        <h4 class="text-center text-black">Delete the comment</h4>
                                        <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                        <div class="d-flex justify-content-center mt-2">
                                            <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                            <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="deleteComment()">Delete</button>
                                        </div>
                                        <input id="d-comment-id" type="hidden" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Side --}}
            <div class="col-3 d-none d-lg-block">
            </div>
            @can('delete', $discussion)
            <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                <div class="modal-dialog rkm-dialog-message" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container h-100">
                                <h4 class="text-center text-black">Delete the discussion</h4>
                                <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                    <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/discussions/d/')">Delete</button>
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
    <script type="text/javascript" src="{{ asset("js/post-scripts.js") }}"></script>
    <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
    <script type="text/javascript">
        bringFullLifeToLinks("post_content");
    </script>
@endpush