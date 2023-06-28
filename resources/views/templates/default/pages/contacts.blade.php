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

    @if(session('adm_form_err'))
        <div class="alert alert-danger" role="alert">
            {{session('adm_form_err')}}
        </div>
    @endif

    @if(session('adm_form_success'))
        <div class="alert alert-success" role="alert">
            {{session('adm_form_success')}}
        </div>
    @endif

    <div class="my-3">
        <form method="post" action="{{route('adm-form', 'dKDxCktKgBHjUjm')}}">
            @csrf
            <div class="mb-3">
                <label for="Name" class="form-label">Name</label>
                <input type="text" class="form-control" id="Name" name="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="email">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" rows="3" name="description"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


@endsection

