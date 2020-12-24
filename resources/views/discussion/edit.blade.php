@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('discussions.update', $discussion->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Edit discussion
                </h2>
                <div class="form-group mt-2 mb-4">
                    <label for="title" class="rkm-control-label">Title</label>
                    <input type="text" name="title" maxlength="125" minlength="6" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title', $discussion->title) }}" placeholder="Enter Title" required />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="content" class="rkm-control-label">Content</label>
                    <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="4" minlength="12" placeholder="Enter Content" required>{{ old('content', $discussion->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="course" class="rkm-control-label">Course</label>
                    <select id="course" name="course" class="custom-select rkm-form-control @error('course') is-invalid @enderror">
                        <option value="0">General</option>
                        @foreach ($class->courses as $c)
                            <option @if(old('course', $discussion->course_id) == $c->id) selected @endif value="{{$c->id}}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                    @error('course')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label class="rkm-control-label">Image</label>
                    <div class="custom-file @error('image') is-invalid @enderror">
                        <input id="discussionImage" type="file" name="image" accept="image/x-png,image/gif,image/jpeg" class="custom-file-input @error('image') is-invalid @enderror" value="{{ old('image') }}">
                        <label class="custom-file-label" for="discussionImage">Choose image</label>
                    </div>
                    @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe mt-5">
                    <button type="submit" class="rb rb-primary rbl w-100">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection