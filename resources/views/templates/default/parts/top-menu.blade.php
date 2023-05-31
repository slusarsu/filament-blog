
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container px-lg-5 ">
        <a class="navbar-brand" href="{{route('home', admLocale())}}">
            {{$admSite['name']}} {{__('front.test')}}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @foreach(admMenu('top-menu') as $item)
                    <li class="nav-item ">
                        <a class="nav-link @if(request()->getRequestUri() == $item->link) active @endif"
                           href="{{$item->link()}}"
                        >
                            {{$item->title}}
                        </a>
                    </li>
                @endforeach

                <li class="nav-item dropdown">
{{--                    <form action="{{route('set-locale')}}" method="post">--}}
                        @csrf
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{admLocale()}}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            @foreach(admLanguages() as $locale => $lang)
                                <li>
                                    <input type="hidden" name="locale" value="{{$locale}}">
                                    <a class="dropdown-item" type="submit" href="{{route('set-locale', $locale)}}">{{$lang}}</a>
                                </li>
                            @endforeach
                        </ul>
{{--                    </form>--}}
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Header-->
