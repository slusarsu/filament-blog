@extends('templates.default.layouts.main')

@section('content')
{{--    @dd($admSite['name'], $admTpl['name'])--}}
    @php
        $posts = admAllPosts(20);
    @endphp
    <div class="row my-2">

        @foreach($posts as $post)

                <div class="col-md-6 g-4">
                    <div class="card p-2" id="post-{{$post->id}}" wire:change="100%">
                        <img src="{{$post->thumb()}}" class="card-img-top" alt="...">
                        <a href="{{$post->url()}}">
                            <h5 class="card-title my-2">{{$post->id}} | {{$post->lang}} | {{$post->title}}</h5>
                        </a>
                        <div>
                            @foreach($post->getTags() as $tag)
                                {{$tag->title}}
                            @endforeach
                        </div>

                        <div class="card-body p-0">
                            <p class="card-text">
                                {{$post->short}}
                            </p>
                        </div>
                        <hr>
                        <div>
                            @if($post->tags)
                                @foreach($post->tags as $tag)
                                    {{$tag->title}}
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>

        @endforeach
    </div>
    <div>
        {!! !empty($posts->links()) ? $posts->links() : '' !!}
    </div>
@endsection
