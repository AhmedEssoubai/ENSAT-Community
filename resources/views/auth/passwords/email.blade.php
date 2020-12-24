@extends('layouts.app')

@section('content')
<section class="container px-0">
    <div class="row mx-0 py-4">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3 p-5">
            <form class="pt-5" method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1 class="mb-5 pt-5 pb-3 text-center">
                    {{ __('Reset Password') }}
                </h1>

                @if (session('status'))
                    <div class="alert alert-success my-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-group my-4">
                    <label for="email" class="rkm-control-label">Email</label>
                    <input id="email" type="email" class="rkm-form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Entrer Email" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-5 text-center">
                    <button type="submit" class="rb-primary rbl w-100 mb-3">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
