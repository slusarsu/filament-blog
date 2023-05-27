@extends('templates.default.layouts.main')

@section('content')

    <h1>{{$post->title}}</h1>
    <img src="{{$post->thumb()}}" alt="" width="200px">
    <p>{!! $post->short !!}</p>
    <p>{!! $post->content !!}</p>

    <div class="d-flex g-4">
        @foreach($post->images() as $image)
            <img src="{{$image}}" alt="" height="200px">
        @endforeach
    </div>

@endsection

