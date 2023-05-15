@extends('templates.default.layouts.main')
@section('content')
    @php $page = admPageBySlug('contacts') @endphp
    <h1>{{$page->title}}</h1>
    <img src="{{$page->thumb->getUrl()}}" alt="" width="200px">

@endsection

