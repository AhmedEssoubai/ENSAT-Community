@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('profile.update', $user->cin) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Account settings
                </h2>
                
                <div class="form-groupe mt-2 mb-4">
                    <label class="rkm-control-label" for="cin">CIN</label>
                    <input id="cin" name="cin" type="text" class="rkm-form-control @error('cin') is-invalid @enderror" placeholder="Enter CIN" value="{{ old('cin') ?? $user->cin }}" required autocomplete="cin" />

                    @error('cin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-4">
                    <label class="rkm-control-label" for="firstname">First Name</label>
                    <input id="firstname" name="firstname" type="text" class="rkm-form-control @error('firstname') is-invalid @enderror" placeholder="Enter First Name" value="{{ old('firstname') ?? $user->firstname }}" required autocomplete="firstname" />

                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-4">
                    <label class="rkm-control-label" for="lastname">Last Name</label>
                    <input id="lastname" name="lastname" type="text" class="rkm-form-control @error('lastname') is-invalid @enderror" placeholder="Enter Last Name" value="{{ old('lastname') ?? $user->lastname }}" required autocomplete="lastname" autofocus />

                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-4">
                    <label class="rkm-control-label" for="email">E-mail</label>
                    <input id="email" type="text" class="rkm-form-control" value="{{ $user->email }}" disabled />
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
        <div class="rkm-line my-5 row"></div>
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('profile.update.security', $user->cin) }}">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">
                    Change password
                </h2>
                
                <div class="form-group  my-3">
                    <label class="rkm-control-label" for="current-password">{{ __('Current Password') }}</label>
                    <input type="password" name="current-password" class="rkm-form-control @error('current-password') is-invalid @enderror" id="current-password" placeholder="Enter The Current Passowrd" required autocomplete="current-password" />

                    @error('current-password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group  my-3">
                    <label class="rkm-control-label" for="password">{{ __('New Password') }}</label>
                    <input type="password" name="password" class="rkm-form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter New Password" required autocomplete="password" />

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group  my-3">
                    <label class="rkm-control-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="rkm-form-control @error('password_confirmation') is-invalid @enderror" placeholder="Conform Password" required autocomplete="password_confirmation" />
                </div>
                <div class="form-groupe mt-5">
                    <button type="submit" class="rb rb-primary rbl w-100">Save password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection