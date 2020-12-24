@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left bg-light page-spacing" id="newGroupe">
    <div class="container">
        <div class="row">
            <div class="col-md-5 p-5 my-3 bg-white shadow">
                <h2 class="mb-2 pb-3">
                    Créer votre groupe maintenant !
                </h2>
                <ul>
                    <li class="py-2">Partager vos connaissence avec les membres du groupe.</li>
                    <li class="py-2">Vous pouvez mettre le groupe privée ou public selon votre choix. </li>
                    <li class="py-2">Vous supprimer le groupe que vous avez créer lorsque vous voulez</li>
                    <li class="py-2">Vous pouvez ajouter des membres aux votre groupe </li>
                </ul>
            </div>
            <div id="forme" class="col-md-5 offset-sm-1 p-5 my-3 bg-white shadow">
                <form method="POST" action="{{ route('classes') }}">
                    @csrf
                    <h2 class="mb-5 pt-5 pb-3">
                        New class
                    </h2>
                    <div class="form-groupe my-3">
                        <label class="control-label" for="label">Class label</label>
                        <div>
                            <input id="label" name="label" type="text" class="form-control @error('label') is-invalid @enderror" placeholder="Enter une label" required autocomplete="nom" autofocus>
                            @error('label')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group my-3">
                        <label for="chef">Chef</label>
                        <select id="chef" name="chef" class="form-control @error('chef') is-invalid @enderror">
                            <option @if(old('chef') == '1') selected @endif value="1">Badir</option>
                        </select>
                    </div>
                    <div class="form-groupe mt-5">
                        <button type="submit" class="btn btn-primary btn-lg form-control">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection