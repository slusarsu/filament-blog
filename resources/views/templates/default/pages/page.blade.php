@extends('templates.default.layouts.main')

@section('content')
    {!! $page->content !!}

    {{$siteSetting->get('name')}}
@endsection

