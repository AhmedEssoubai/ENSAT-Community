@extends('layouts.app')

@section('content')
<div class="page-spacing">
    <div class="container py-5 my-5">
        <div class="row justify-content-md-center">
            <form class="col-sm-12 col-md-8 col-lg-6" method="POST" action="{{ route('groups.update', $group->id) }}">
                @csrf
                @method('PATCH')
                <h2 class="mb-5 text-center text-black">Edit group</h2>
                @csrf
                <div class="form-group mt-2 mb-4">
                    <input id="group_label" type="text" name="label" maxlength="64" class="rkm-form-control my-2 @error('label') is-invalid @enderror" value="{{ old('label', $group->label) }}" placeholder="Label" onInput="selected_changed(selected.length)" required />
                    @error('label')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group my-4">
                    <div id="selected_list" class="d-flex flex-wrap">
                        @foreach ($group->students as $student)
                            <button id="selected_prof_{{ $student->id }}" type="button" class="free-list border rounded-pill mr-3 my-2" onclick="removeItem({{ $student->id }})">
                                <div class="d-flex p-1 align-items-center">
                                    <input type="hidden" name="students[]" value="{{ $student->id }}">
                                    <div class="pr-3">
                                        <img src="{{ $student->user->image }}" class="img-fluid rounded-circle" width="28px">
                                    </div>
                                    <span class="small text-muted pr-2">{{ $student->user->firstname }} {{ $student->user->lastname }}</span>
                                </div>
                            </button>
                        @endforeach
                        <input id="search_input" class="free lead flex-grow-1 my-2" maxlength="45" placeholder="Search for students..."/>
                    </div>
                    <div class="border-bottom my-4"></div>
                    <div style="height: 25vh" class="overflow-auto">
                        <div id="empty_list" class="p-3 align-items-center d-none">
                            <h6 class="text-muted"><span class="mr-3 lead"><i class="fas fa-user-slash"></i></span>Oops! no students found</h6>
                        </div>
                        <div id="results_list" class="py-3 container"></div>
                    </div>
                    @error('students')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-groupe mt-5">
                    <button id="submit_form" type="submit" class="rb rb-primary rbl w-100" disabled>Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/members-scripts.js') }}"></script>
    <script>
        src = "/search/students/{{ $class->id}}/";
        selected_name = "students";
        @foreach ($group->students as $student)
        selected.push({{ $student->id }});
        @endforeach
        selected_changed = function(count) {
            document.getElementById("submit_form").disabled = count == 0 || isEmpty(document.getElementById("group_label").value);
        };
    </script>
@endpush