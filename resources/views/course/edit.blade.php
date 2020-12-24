@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('courses.update', $course->id) }}">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Edit course
                </h2>
                <div class="form-groupe mt-2 mb-4">
                    <label for="title" class="rkm-control-label">Title</label>
                    <input id="title" type="text" name="title" maxlength="125" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title)  }}" placeholder="Enter Title" required />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="short_title" class="rkm-control-label">Short Title</label>
                    <input id="short_title" type="text" name="short_title" maxlength="125" class="rkm-form-control @error('short_title') is-invalid @enderror" value="{{ old('short_title', $course->short_title) }}" placeholder="Enter Short Title" required />
                    @error('short_title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="color" class="rkm-control-label">Color</label>
                    <input id="color" type="color" name="color" class="rkm-form-control @error('color') is-invalid @enderror" value="{{ old('color', $course->color) }}" placeholder="Enter Short Title" required />
                    @error('color')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="description" class="rkm-control-label">Description</label>
                    <textarea id="description" class="rkm-form-control @error('description') is-invalid @enderror" name="description" rows="3" cols="30" maxlength="255" placeholder="Enter Description" required>{{ old('description', $course->description) }}</textarea>
                    @error('description')
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