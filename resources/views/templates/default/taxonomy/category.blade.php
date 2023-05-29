@extends('templates.default.layouts.main')

@section('content')

    <h1>{{$category->title}} ({{$category->posts_count}})</h1>

    <div class="row my-2">
        @foreach($posts as $post)

            <div class="col-md-6 g-4">
                <div class="card p-2" id="post-{{$post->id}}" wire:change="100%">
                    <img src="{{$post->thumb()}}" class="card-img-top" alt="...">
                    <a href="{{$post->url()}}">
                        <h5 class="card-title my-2">{{$post->title}}</h5>
                    </a>

                    <div class="card-body p-0">
                        <p class="card-text">
                            {{$post->short}}
                        </p>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
    <div>
        {!! !empty($posts->links()) ? $posts->links() : '' !!}
    </div>

@endsection
