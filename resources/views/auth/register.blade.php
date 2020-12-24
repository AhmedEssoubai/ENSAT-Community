@extends('layouts.app')

@section('content')
<section class="container px-0">
    <div class="row mx-0 py-4">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3 p-5">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <h1 class="pt-5 pb-3 text-center">
                    {{ __('Register') }}
                </h1>

                <div class="toggle-options mb-5 mx-auto">
                    <button id="btn-std" type="button" onclick="account_type(0)" class="toggle-option active">Student</button>
                    <button id="btn-prf" type="button" onclick="account_type(1)" class="toggle-option">Professor</button>
                </div>
                <input id="atype" type="hidden" name="atype" value="{{ old('atype', '0') }}" />
                <div id="std-inputs">
                    <div class="form-group my-3">
                        <label for="class" class="rkm-control-label">Class</label>
                        <select id="class" name="class" class="custom-select rkm-form-control @error('class') is-invalid @enderror">
                            <option disabled @empty(old('class')) selected @endempty value>-- Select class --</option>
                            @isset($classes)
                                @foreach ($classes as $c)
                                    <option @if(old('class') == '{{$c->id}}') selected @endif value="{{$c->id}}">{{$c->label}}</option>
                                @endforeach
                            @endisset
                        </select>

                        @error('class')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div id="prf-inputs">
                </div>
                <div class="form-groupe my-3">
                    <label class="rkm-control-label" for="cin">CIN</label>
                    <input id="cin" name="cin" type="text" class="rkm-form-control @error('cin') is-invalid @enderror" placeholder="Enter CIN" value="{{ old('cin') }}" required autocomplete="cin" />

                    @error('cin')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-3">
                    <label class="rkm-control-label" for="firstname">First Name</label>
                    <input id="firstname" name="firstname" type="text" class="rkm-form-control @error('firstname') is-invalid @enderror" placeholder="Enter First Name" value="{{ old('firstname') }}" required autocomplete="firstname" />

                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-3">
                    <label class="rkm-control-label" for="lastname">Last Name</label>
                    <input id="lastname" name="lastname" type="text" class="rkm-form-control @error('lastname') is-invalid @enderror" placeholder="Enter Last Name" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus />

                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe my-3">
                    <label class="rkm-control-label" for="email">{{ __('E-Mail Address') }}</label>
                    <input id="email" name="email" type="text" class="rkm-form-control @error('email') is-invalid @enderror" placeholder="Enter Email" value="{{ old('email') }}" required autocomplete="email" />

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group  my-3">
                    <label class="rkm-control-label" for="password">{{ __('Password') }}</label>
                    <input type="password" name="password" class="rkm-form-control @error('password') is-invalid @enderror" id="password" placeholder="Enter Password" required autocomplete="password" />

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
                    <button type="submit" class="rb rb-primary rbl w-100 mb-3">{{ __('Register') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
    var type = 0;

    function account_type(t)
    {
        type = t;
        document.getElementById("atype").value = t;
        if (type === 1)
        {
            document.getElementById("prf-inputs").classList.remove("d-none");
            document.getElementById("std-inputs").classList.add("d-none");
            document.getElementById("btn-std").classList.remove("active");
            document.getElementById("btn-prf").classList.add("active");
        }
        else
        {
            document.getElementById("std-inputs").classList.remove("d-none");
            document.getElementById("prf-inputs").classList.add("d-none");
            document.getElementById("btn-prf").classList.remove("active");
            document.getElementById("btn-std").classList.add("active");
        }

    }

    account_type(document.getElementById("atype").value);
</script>
@endsection
