@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('classes.update', $class->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Class details
                </h2>
                <div class="form-groupe mt-2 mb-4">
                    <label for="label" class="rkm-control-label">Label</label>
                    <input id="label" name="label" type="text" class="rkm-form-control @error('label') is-invalid @enderror" value="{{ old('label') ?? $class->label }}" placeholder="Enter Label" required autocomplete="label" autofocus/>
                    @error('label')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <label for="chef" class="rkm-control-label">Class Cheff</label>
                    <select id="chef" name="chef" class="custom-select rkm-form-control @error('chef') is-invalid @enderror" required>
                        <option disabled @empty(old('chef') ?? $class->chef_id) selected @endempty value>-- Select class chef --</option>
                        @foreach ($professors as $professor)
                            <option @if((old('chef') ?? $class->chef_id) == '{{$professor->id}}') selected @endif value="{{$professor->id}}">{{$professor->user->firstname}} {{$professor->user->lastname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group my-4">
                    <label class="rkm-control-label">Image</label>
                    <div class="custom-file @error('image') is-invalid @enderror">
                        <input id="discussionImage" type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" value="{{ old('image') }}">
                        <label class="custom-file-label rkm-custom-file-label" for="discussionImage">Choose image</label>
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