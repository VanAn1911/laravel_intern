<html>
  <head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @stack('styles')
  </head>
  <body>
    @include('layouts.header')
    @include('layouts.navbar')
    <div class="container">
        @yield('content')
    </div>
    @stack('scripts')
    @include('layouts.footer')
  </body>
</html>
