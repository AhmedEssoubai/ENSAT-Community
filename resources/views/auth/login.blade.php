@extends('layouts.app')

@section('content')
<section class="container px-0">
    <div class="row mx-0 py-4">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3 p-5">
            <form class="pt-5" method="POST" action="{{ route('login') }}">
                @csrf
                <h1 class="mb-5 pt-5 pb-3 text-center">
                    Welcome Back!
                </h1>

                <div class="form-group my-4">
                    <label for="email" class="rkm-control-label">Email</label>
                    <input id="email" type="email" class="rkm-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Entrer Email" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group my-4">
                    <label for="password" class="rkm-control-label">Password</label>
                    <input id="password" type="password" class="rkm-form-control @error('password') is-invalid @enderror" placeholder="Entrer Password" name="password" required autocomplete="current-password">
                    
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group my-4">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="custom-control-label text-mgray" for="remember">
                            <small>Remember me</small>
                        </label>
                    </div>
                </div>

                <div class="form-group mt-5 text-center">
                    <button type="submit" class="rb rb-primary rbl w-100 mb-3">
                    Log in
                    </button>
                    <!--@if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif-->
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
