@include('templates.default.parts.header')
@include('templates.default.parts.top-menu')

<x-adm.widget :slug="'header-block'"/>

<!-- Page Content-->
<section class="pt-4">
    <div class="container px-lg-5">
        <!-- Page Features-->
        @yield('content')
    </div>
</section>

@include('templates.default.parts.footer')
