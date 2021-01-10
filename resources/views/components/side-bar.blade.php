{{-- Assignments list --}}
@if (Auth::user()->isStudent())
<div class="py-3 mb-4 border-rounded">
    <h6 class="text-dark mx-3 mb-3">Required assignments soon</h6>
    <small class="text-mgray mx-3 mb-3">This week assignments</small>
    @if ($tw_assignments->count())
        <div class="rkm-list-group">
            @foreach ($tw_assignments as $a)
                <a href="{{ route('assignments.show', $a->id) }}" class="list-group-item d-flex align-items-center border-0">
                    <small class="line-clamp mr-1">{{ $a->title }}</small>
                    <small><strong>{{ $a->deadline->format('h:i A') }}</strong></small>
                </a>
            @endforeach
        </div>
    @else
        <div class="my-3 text-center">
            <small class="text-lgray">No assignments required this week.</small>
        </div>
    @endif
    <small class="text-mgray mx-3 mb-3">Next week assignments</small>
    @if ($nw_assignments->count())
        <div class="rkm-list-group">
            @foreach ($nw_assignments as $a)
                <a href="{{ route('assignments.show', $a->id) }}" class="list-group-item d-flex align-items-center border-0">
                    <small class="line-clamp mr-1">{{ $a->title }}</small>
                    <small><strong>{{ $a->deadline->format('h:i A') }}</strong></small>
                </a>
            @endforeach
        </div>
    @else
        <div class="my-3 text-center">
            <small class="text-lgray">No assignments required next week.</small>
        </div>
    @endif
    <div class="text-center mt-3">
        <a href="{{ route('classes.assignments', $class->id) }}" class="_link px-3"><small>Show all</small></a>
    </div>
</div>
@endif
{{-- Students list --}}
<div class="py-3 mb-4 border-rounded">
    <h6 class="text-dark mx-3 mb-3">Class students</h6>
    <div class="container">
        <div class="row mx-1 position-relative">
            @foreach ($students as $student)
                <div class="col-2 my-1 px-1">
                    <img class="img-fluid rounded-circle" src="{{ $student->user->image }}" alt="student_img" title="{{ $student->user->firstname }} {{ $student->user->lastname }}">
                </div>
            @endforeach
            <div class="more"></div>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('classes.members', $class->id) }}" class="_link px-3"><small>Show all</small></a>
    </div>
</div>
{{-- Announcements list --}}
<div class="py-3 mb-4 border-rounded">
    <h6 class="text-dark mx-3 mb-3">Latest announcements</h6>
    @if ($lt_announcements->count())
        <div class="rkm-list-group">
            @foreach ($lt_announcements as $an)
                <a href="{{ route('assignments.show', $an->id) }}" class="list-group-item d-flex align-items-center border-0">
                    <small class="line-clamp mr-1">{{ $an->title }}</small>
                </a>
            @endforeach
        </div>
    @else
        <div class="my-3 text-center">
            <small class="text-lgray">There is no new announcements.</small>
        </div>
    @endif
    <div class="text-center mt-3">
        <a href="{{ route('announcements') }}" class="_link px-3"><small>Show all</small></a>
    </div>
</div>