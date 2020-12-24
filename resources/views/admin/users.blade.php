@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">Pending</h3>
                <p class="text-muted">
                    There are <span id="count">{{ $pending->count() }}</span> @if ($user_tab_index == 0) students @else professors @endif in pending
                </p>
            </div>
        </div>
        <div id="list" class="row mt-3">
            @if ($pending->count())
                @foreach($pending as $user)
                    <div id="pending{{ $loop->index }}" class="col-md-6 my-3 d-flex align-content-stretch">
                        <div class="course-card m-1 flex-grow-1">
                            <div class="p-4 w-100">
                                <div class="d-flex align-items-center">
                                    <div class="pr-3">
                                        <img src="{{ $user->image }}" class="img-fluid rounded-circle" width="78px"/>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>{{ $user->firstname }} {{ $user->lastname }}</h5>
                                        @if ($user_tab_index == 0)
                                            <p class="text-mgray">{{ $user->profile->classe->label }}</p>
                                        @endif
                                    </div>
                                    <div class="align-self-center d-flex">
                                        <div class="mx-4">
                                            <form action="{{ route('admin.users.accept', ['user' => $user->cin]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-free p-0" title="Accept"><span class="icon-g"><i class="fa fa-check"></i></span></button>
                                            </form>
                                        </div>
                                        <div class="mx-3 icon-r"><a title="Reject" data-toggle="modal" data-target="#delete_alert" data-id="{{ $user->cin }}"><span><i class="fa fa-times"></i></span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col text-muted text-center py-2 px-2">
                    <h2 class="my-3" style="font-size: 3em"><i class="fab fa-cloudversify"></i></h2>
                    <h4 class="my-3">No users in pending list</h4>
                </div>
            @endif
        </div>
        <div class="rkm-line mt-5"></div>
        <div class="row mx-3 mt-5 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">Members</h3>
                <p class="text-muted">
                    There are {{ $members->count() }} member
                </p>
            </div>
        </div>
        <div class="row mt-3">
            @if ($members->count())
                @foreach($members as $user)
                    <div id="member{{ $loop->index }}" class="col-md-6 my-3 d-flex align-content-stretch">
                        <div class="course-card m-1 flex-grow-1">
                            <div class="p-4 w-100">
                                <div class="d-flex align-items-center">
                                    <div class="pr-3">
                                        <img src="{{ $user->image }}" class="img-fluid rounded-circle" width="78px"/>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>{{ $user->firstname }} {{ $user->lastname }}</h5>
                                        @if ($user_tab_index == 0)
                                            <p class="text-mgray">{{ $user->profile->classe->label }}</p>
                                        @endif
                                    </div>
                                    @can('delete', $user)
                                        <div class="d-flex pr-3 align-items-center dropdown">
                                            <span class="icon-mute icon-hidden" id="member_{{ $loop->index }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></span>
                                            <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="member_{{ $loop->index }}_options">
                                                <a class="dropdown-item rkm-dropdown-item" href="{{ route('profile.edit', $user->cin) }}">Edit</a>
                                                <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $user->cin }}" href="">Delete</a>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-lgray lead mr-2" title="Admin"><i class="fas fa-user-shield"></i></span>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col text-muted text-center py-2 px-2">
                    <h3 class="col my-4 text-muted text-center"><i class="far fa-frown"></i> The community is empty</h3>
                </div>
            @endif
        </div>
        <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
            <div class="modal-dialog rkm-dialog-message" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="container h-100">
                            <h4 class="text-center text-black">Delete the user</h4>
                            <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                <a type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/users/d/')">Delete</a>
                            </div>
                            <input id="d-item-id" type="hidden" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        var count = {{ $pending->count() }};
    </script>
    <script src="{{ asset('js/alert-scripts.js') }}"></script>
@endpush