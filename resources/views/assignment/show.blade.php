@extends('layouts.app')

@section('content')
<section id="content" class="pt-3 pb-5 text-left mx-auto bg-white page-spacing">
    <div class="container pt-3">
        <div class="row">
            <div class="col-md-9 p-0">
                <div class="post">
                    <div class="mr-3 avatar-40">
                        <img src="{{ $assignment->course->professor->user->image }}" alt="profile image" class="img-fluid rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <strong class="text-dgray my-0 mr-2">{{ $assignment->course->professor->user->firstname }} {{ $assignment->course->professor->user->lastname }}</strong>
                                <strong class="text-mgray mr-2"> • </strong>
                                <small class="text-mgray">{{ $assignment->created_at->diffForHumans() }}</small>
                            </div>
                            @switch($assignment->getStatus(Auth::user()))
                                @case('submitted')
                                    <div class="tag tag-success text-up text-bold">submitted</div>
                                    @break
                                @case('all submitted')
                                    <div class="tag tag-primary text-up text-bold">all submitted</div>
                                    @break
                            @endswitch
                        </div>
                        <div class="mt-2">
                            <small class="mr-3 text-red">Deadline: {{ $assignment->deadline }} (@if (!$assignment->isClosed()) {{ $assignment->deadline->diffForHumans([
                                'syntax' => Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                                'options' => Carbon\Carbon::JUST_NOW | Carbon\Carbon::ONE_DAY_WORDS | Carbon\Carbon::TWO_DAY_WORDS,
                            ]) }}
                            @else
                            closed
                            @endif)</small>
                            <h4 class="text-black my-3">{{ $assignment->title }}</h4>
                            <h6 class="text-black mb-3">
                                <a href="{{ route('classes.assignments', $class->id) }}?filter={{ $assignment->course->id }}" class="mr-2 link-animation _link text-dgray"><strong>{{ $assignment->course->title }}</strong></a>
                            </h6>
                            <p id="post_content" class="text-mgray mb-3">{{ $assignment->objectif }}</p>
                            {{--@if (!empty($assignment->image))
                                <img src="/storage/{{ $discussion->image }}" class="img-fluid mb-3" alt="discussion image">
                            @endif--}}
                            {{-- Attachments --}}
                            @if ($assignment->files->count() > 0)
                                <div class="mt-4 d-flex flex-wrap">
                                    @foreach($assignment->files as $file)
                                        <a href="{{ route('files.assignment', $file->id) }}" target="_blank" class="btn btn-os mr-4 my-2"><i class="fas fa-file-download mr-2"></i> {{ $file->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            @can('delete', $assignment)
                                <div class="d-flex align-items-center dropdown">
                                    <span class="text-mgray icon-hidden" id="assignment_{{ $assignment->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></span>
                                    <div class="dropdown-menu rkm-dropdown-menu" aria-labelledby="assignment_{{ $assignment->id }}_options">
                                        {{--@can('update', $assignment)
                                            <a class="dropdown-item rkm-dropdown-item" href="{{ route('assignments.edit', $assignment->id) }}">Edit</a>
                                        @endcan--}}
                                        <a class="dropdown-item rkm-dropdown-item" data-toggle="modal" data-target="#delete_alert" data-id="{{ $assignment->id }}" href="">Delete</a>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
                @can('delete', $assignment)
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
            @can('create', App\Submission::class)
                <div class="col-md-3">
                    {{-- Submission --}}
                    <div class="py-3 mb-4 border-rounded">
                        <h6 class="text-dark mx-3 mb-3">Your submission</h6>
                        @isset ($submission)
                            @if ($submission->files->count())
                                <div class="mt-4 text-center">
                                    <div id="sub_files_list" class="attachments-small my-4">
                                    @foreach($submission->files as $file)
                                        <a href="{{ route('files.submission', $file->id) }}" target="_blank" class="text-dgray attachment-box">
                                            <div class="line-clamp" title="2000px-QlikTech_20xx_logo.svg_.png" title="{{ $file->name }}">
                                                <span class="mr-4 text-mgray"><i class="fas fa-paperclip"></i></span>{{ $file->name }}
                                            </div>
                                        </a>
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <form id="submit-form" method="POST" enctype="multipart/form-data" action="{{ route('submissions') }}">
                                @csrf
                                <input type="hidden" name="assignment" value="{{ $assignment->id }}"/>
                                <div class="mt-4 text-center">
                                    <div id="sub_files_list" class="attachments-small my-4">
                                    </div>
                                    <button type="button" onclick="create_attachment('sub_files_list')" class="rbo-secondary border-0 pl-3"><span class="mr-2"><i class="fas fa-plus"></i></span> Add files</button>
                                </div>
                                <div class="mt-4 d-flex justify-content-center">
                                    <button id="submit_work" type="button" class="rb rb-primary text-up text-bold" data-toggle="modal" data-target="#submit_alert" disabled>submit</button>
                                </div>
                            </form>
                        @endisset
                    </div>
                </div>
                <div class="modal fade rkm-model delete-alert" id="submit_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                    <div class="modal-dialog rkm-dialog-message" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="container h-100">
                                    <h4 class="text-center text-black">Submit your work</h4>
                                    <div class="text-center text-mgray mb-4">Once you submit, you can't change your submission. Are you sure of this?</div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                        <button type="submit" class="rb rb-primary mx-2" data-dismiss="modal" onclick="event.preventDefault();
                                        document.getElementById('submit-form').submit();">Submit</button>
                                    </div>
                                    <input id="d-item-id" type="hidden" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('delete', $assignment)
                <div class="modal fade rkm-model delete-alert" id="delete_alert" tabindex="-1" role="dialog" aria-labelledby="dp-modalLabel" aria-hidden="true">
                    <div class="modal-dialog rkm-dialog-message" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="container h-100">
                                    <h4 class="text-center text-black">Delete the assignment</h4>
                                    <div class="text-center text-mgray mb-4">Are you sure of this?</div>
                                    <div class="d-flex justify-content-center mt-2">
                                        <button type="button" class="rbo-secondary mx-2" data-dismiss="modal">Close</button>
                                        <button type="submit" class="rb rb-danger mx-2" data-dismiss="modal" onclick="send_action('/index.php/assignments/d/')">Delete</button>
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
    <script type="text/javascript">
        bringFullLifeToLinks("post_content");
    </script>
    @if (Auth::user()->isProfessor())
        <script type="text/javascript" src="{{ asset("js/alert-scripts.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/views-history-scripts.js") }}"></script>
        <script type="text/javascript">
            item = {{ $assignment->id }};
            base_link = 'assignments';
            files = [
                @foreach ($assignment->files as $file)
                    '{{ $file->name }}', 
                @endforeach
            ];
        </script>
    @else
        <script type="text/javascript" src="{{ asset("js/files-scripts.js") }}"></script>
        <script type="text/javascript" src="{{ asset("js/submission-scripts.js") }}"></script>
    @endif
@endpush