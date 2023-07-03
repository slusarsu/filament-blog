
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
                @foreach(admMenuByPosition('header') as $item)
                    <li class="nav-item ">
                        <a class="nav-link @if(request()->getRequestUri() == $item->link) active @endif"
                           href="{{$item->link()}}"
                        >
                            {{$item->title}}
                        </a>
                    </li>
                @endforeach

                @if(auth()->check())
                    <li class="nav-item dropdown">

                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{auth()->user()->name}}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li>
                                <a href="{{url('/admin')}}" class="dropdown-item">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <form action="{{url('/filament/logout')}}" method="post">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        Logout
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </li>
                @else
                    <li class="nav-item ">
                        <a class="nav-link"  href="{{url('/admin/login')}}">
                            Login
                        </a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <form action="{{route('switch-locale')}}" method="post">
                        @csrf

                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{admLocale()}}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-dark">

                            <input type="hidden" name="route_name" value="{{admRouteName()}}">
                            <input type="hidden" name="route_parameters" value="{{admJsonRouteParameters()}}">

                            @foreach(admLanguages() as $locale => $lang)
                                @if($locale == admLocale())
                                    @continue(1)
                                @endif
                                <li>
                                    <button class="dropdown-item" type="submit" name="locale" value="{{$locale}}">
                                        {{$lang}}
                                    </button>
                                </li>
                            @endforeach

                        </ul>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Header-->
