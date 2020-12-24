@extends('layouts.app')

@section('content')
<section>
    <div class="page-spacing">
        <div id="cover" style="background-image: url('{{ $class->image }}')">
            <div id="cover-overlay">
                <div class="d-flex flex-column h-100">
                    <h1 class="text-white m-auto text-center display-3 ">{{ $class->label }}</h1>
                    <ul id="class-nav">
                        <li class="nav-item 
                            @if ($sub_tab_index == 0)
                                active
                            @endif">
                            <a class="nav-link px-md-5" href="{{ route('classes.discussions', $class->id) }}"><i class="fas fa-users mr-2"></i> Discussions</a><!--friends-->
                            <div class="indicator"></div>
                        </li>
                        <li class="nav-item 
                            @if ($sub_tab_index == 1)
                                active
                            @endif">
                            <a class="nav-link px-md-5" href="{{ route('classes.resources', $class->id) }}"><i class="fas fa-folder mr-2"></i> Resources</a>
                            <div class="indicator"></div>
                        </li>
                        <li class="nav-item 
                            @if ($sub_tab_index == 2)
                                active
                            @endif">
                            <a class="nav-link px-md-5" href="{{ route('classes.assignments', $class->id) }}"><i class="fas fa-clipboard-list mr-2"></i> Assigments</a>
                            <div class="indicator"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="content" class="py-5 text-left mx-auto">
        <div class="container">
            <div class="row">
                @yield('content-2')
            </div>
        </div>
    </div>
</section>
@endsection