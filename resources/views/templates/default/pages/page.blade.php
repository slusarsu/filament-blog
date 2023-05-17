@extends('templates.default.layouts.main')

@section('content')
    @php
        $page = admPageBySlug('blog');
        $cf = $page->customFields();
    @endphp
    <h1>{{$page->title}}</h1>
    <img src="{{$page->thumb->getUrl()}}" alt="" width="200px">
    <p>{!! $page->short !!}</p>
    <p>{!! $page->content !!}</p>
@endsection

