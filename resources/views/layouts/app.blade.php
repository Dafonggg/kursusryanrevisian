<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>@yield('title', 'Kursus Ryan Komputer')</title>

        @include('partials.styles')
    </head>
    
    <body @yield('body-class', '') id="top">
        <main>
            @include('components.navbar')

            @yield('content')

            @include('components.footer')
        </main>

        @include('partials.scripts')
    </body>
</html>

