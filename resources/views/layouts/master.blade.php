<html>
  <head>
    <title>@yield('title')</title>
    @stack('styles')
  </head>
  <body>
    @include('layouts.navbar')
    <div class="container">
        @yield('content')
    </div>
    @stack('scripts')
  </body>
</html>
