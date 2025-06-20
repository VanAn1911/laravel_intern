<html>
  <head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @stack('styles')
  </head>
  <body>
    <div class="container">
        @yield('content')
    </div>
    @stack('scripts')
    @include('layouts.footer')
  </body>
</html>
