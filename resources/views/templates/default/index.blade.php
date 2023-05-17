@extends('templates.default.layouts.main')

@section('content')
    @php
        $params = [
            'limit' => 4,
        ];
        $posts = admAllPosts(0, $params);
    @endphp
    @foreach($posts as $post)
        <div class="my-2">
            {{$post->id}} |
            {{$post->title}}
        </div>
    @endforeach
    <div>
{{--        {!! !empty($posts->links()) ? $posts->links() : '' !!}--}}
    </div>
@endsection
