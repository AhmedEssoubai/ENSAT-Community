@extends('layouts.app')

@section('content')
<section class="pt-3 pb-5 text-left page-spacing bg-light">
    <div class="container pt-3">
        <div class="row mx-3 mt-2 mb-3 pb-3 d-flex justify-content-between">
            <div>
                <h3 class="text-black">Announcements</h3>
                <p class="text-muted">
                    You have 0
                </p>
            </div>
        </div>
        <div class="row mb-5 chronological-list">
            <div class="col-12 mb-3 d-flex">
                <div class="px-4 py-2 text-dgray mx-auto border-bottom border-primary lead text-bold" style="border-width: 2px !important">2020</div>
            </div>
            <div class="col-12 chronological-section">
                <div class="chronological-date">
                    <div class="d-flex">
                        <div class="chronological-date-box mr-2 text-dgray text-up" style="font-size: 0.75rem; font-weight: 700">
                            DEC
                        </div>
                        <div class="chronological-date-box text-dgray text-up">
                            20
                        </div>
                    </div>
                    <div class="text-dgray text-right">
                        <small>20 hours ago</small>
                    </div>
                </div>
                <div class="chronological-contents">
                    <div class="chronological-contents-item">
                        <div class="chronological-contents-type">
                            <div class="chronological-contents-type-icon text-dgray"><i class="fas fa-bullhorn"></i></div>
                        </div>
                        <h4><a class="_link" href="">Ipsum dolor sit amet consectetur adipisicing elit.</a></h4>
                        <div class="mt-2 mb-3 text-dark">by Hassan Badir</div>
                        <div class="text-dgray">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Explicabo, voluptatem minus. Cupiditate alias tenetur temporibus ratione mollitia nobis, consequatur assumenda, dignissimos iusto officiis saepe debitis adipisci dolore perferendis! Pariatur, minima?
                        </div>
                    </div>
                    <div class="chronological-contents-item">
                        <div class="chronological-contents-type">
                            <div class="chronological-contents-type-icon text-dgray"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                        <h4><a class="_link" href="">Ipsum dolor sit amet consectetur adipisicing elit.</a></h4>
                        <div class="mt-2 mb-3 text-dark">by Hassan Badir</div>
                        <div class="text-dgray">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Explicabo, voluptatem minus. Cupiditate alias tenetur temporibus ratione mollitia nobis, consequatur assumenda, dignissimos iusto officiis saepe debitis adipisci dolore perferendis! Pariatur, minima?
                        </div>
                    </div>
                    <div class="chronological-contents-item">
                        <div class="chronological-contents-type">
                            <div class="chronological-contents-type-icon text-dgray"><i class="fas fa-star"></i></div>
                        </div>
                        <h4><a class="_link" href="">Ipsum dolor sit amet consectetur adipisicing elit.</a></h4>
                        <div class="mt-2 mb-3 text-dark">by Hassan Badir</div>
                        <div class="text-dgray">
                            Lorem ipsum dolor sit, amet consectetur adipisicing elit. Explicabo, voluptatem minus. Cupiditate alias tenetur temporibus ratione mollitia nobis, consequatur assumenda, dignissimos iusto officiis saepe debitis adipisci dolore perferendis! Pariatur, minima?
                        </div>
                    </div>
                </div>
            </div>
            {{--@foreach ($my_classes as $c)
            <div class="col-sm-6 col-md-4 col-lg-4 my-3">
                <div class="class-card card">
                    <div class="card-img-top img_box"><a href="{{ route('classes.discussions', $c->id) }}"><img class="img_self" src="{{ $c->image }}" alt="class image"></a></div>
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            <a class="link-dark" href="{{ route('classes.discussions', $c->id) }}">{{ $c->label }}</a>
                            @can('update', $c)
                            <div class="d-flex ml-2 align-items-center dropdown">
                                <small class="text-mgray icon-hidden" id="class_{{ $c->id }}_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></small>
                                <div class="dropdown-menu dropdown-menu-right rkm-dropdown-menu" aria-labelledby="class_{{ $c->id }}_options">
                                    <a class="dropdown-item rkm-dropdown-item" href="{{ route('classes.edit', $c->id) }}">Edit</a>
                                </div>
                            </div>
                            @endcan
                        </h5>
                        <div class="content-hidden my-3 d-flex text-lgray">
                            <div class="mr-4" title="Students">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-user-graduate mr-1"></i> {{ $c->students_count }}</span>
                            </div>
                            <div class="mr-4" title="Resources">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-book mr-1"></i> {{ $c->resources_count }}</span>
                            </div>
                            <div class="mr-4" title="Assignments">
                                <span class="content-hidden-item align-items-center"><i class="fas fa-file-alt mr-1"></i> {{ $c->assignments_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach--}}
        </div>
    </div>
</section>
@endsection