@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('announcements.update', $announcement->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Edit announcement
                </h2>
                <div class="form-group mt-2 mb-4">
                    <label for="title" class="rkm-control-label">Title</label>
                    <input type="text" name="title" maxlength="125" class="rkm-form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" placeholder="Enter Title" required />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="content" class="rkm-control-label">Content</label>
                    <textarea id="content" class="rkm-form-control @error('content') is-invalid @enderror" name="content" rows="4" placeholder="Enter Content" required>{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-3" style="border-bottom: 1px solid rgba(114, 114, 114, 0.5)">
                    <label for="classes-btn" class="rkm-control-label">Classes</label>
                    <button id="classes-btn" type="button" class="btn-free w-100 text-left text-dgray lead mb-2 @error('classes') is-invalid @enderror">
                        Classes
                    </button>
                    <div id="classes-list" style="display: none; border-top: 1px solid rgba(114, 114, 114, 0.2)">
                        <div class="custom-control custom-checkbox my-3">
                            <input type="checkbox" class="custom-control-input" id="opt-0" checked>
                            <label class="custom-control-label w-100" for="opt-0">All classes</label>
                            <div id="targets-list">
                                @isset($classes)
                                    @foreach ($classes as $c)
                                        <div><input type="checkbox" name="classes[]" value="{{$c->id}}" class="custom-control-input" id="opt-{{$c->id}}" @if(old('class', $c->id) == $c->id) checked @endif>
                                        <label class="custom-control-label w-100" for="opt-{{$c->id}}">{{$c->label}}</label></div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>
                    </div>
                    @error('classes')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe mt-5">
                    <button id="btn-submit" type="submit" class="rb rb-primary rbl w-100" disabled>Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset("js/files-scripts.js") }}"></script>
    <script type="text/javascript" src="{{ asset("js/announcement-form.js") }}"></script>
@endpush