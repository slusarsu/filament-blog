@extends('templates.default.layouts.main')

@section('content')

    <h1>{{$page->title}}</h1>
    <img src="{{$page->getFirstMediaUrl('thumbs')}}" alt="" width="200px">
    <p>{!! $page->short !!}</p>
    <p>{!! $page->content !!}</p>

    <div class="d-flex g-4">
        @foreach($page->getMedia('images') as $image)
            <img src="{{$image->getUrl()}}" alt="" height="200px">
        @endforeach
    </div>

@endsection

