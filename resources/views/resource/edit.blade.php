@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('resources.update', $resource->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Edit resource
                </h2>
                <div class="form-group mt-2 mb-4">
                    <label for="title" class="rkm-control-label">Title</label>
                    <input type="text" name="title" maxlength="125" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title', $resource->title) }}" placeholder="Enter Title" required />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="content" class="rkm-control-label">Content</label>
                    <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="4" placeholder="Enter Content" required>{{ old('content', $resource->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="course" class="rkm-control-label">Course</label>
                    <select id="course" name="course_id" class="custom-select rkm-form-control @error('course_id') is-invalid @enderror">
                        @foreach ($prof_courses as $course)
                            <option @if(old('course_id', $resource->course_id) == $course->id) selected @endif value="{{$course->id}}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mt-3">
                    <button type="button" onclick="create_attachment('attachments_list')" class="rbo-secondary mt-3 pl-3"><span class="mr-2"><i class="fas fa-paperclip"></i></span> Add attachment</button>
                    <div id="attachments_list" class="my-4">
                        @foreach ($resource->files as $file)
                            <div id="attachment_{{ $loop->index }}" class="text-dgray attachment-box">
                                <input type="hidden" name="eattachments[]" value="{{ $file->id }}">
                                <div class="line-clamp" title="{{ $file->name }}">
                                    <span class="mr-4 text-mgray"><i class="fas fa-paperclip"></i></span>{{ $file->name }}
                                </div>
                                <button type="button" class="icon-hidden icon-delete btn-free" onclick="delete_attachment({{ $loop->index }})">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-groupe mt-5">
                    <button type="submit" class="rb rb-primary rbl w-100">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset("js/files-scripts.js") }}"></script>
    <script type="text/javascript">
        attachment_id = {{ $resource->files->count() }};
        attachment_count = {{ $resource->files->count() }};
    </script>
@endpush