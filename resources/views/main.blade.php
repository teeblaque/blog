<!DOCTYPE html>
<html lang="en">
  <head>
    @include('partials._head')
  </head>

  <body>
    @include('partials._nav')
        <div class="container">
          @include('partials._messages')

          {{-- Auth::check() ? 'Logged In '.Auth::user()->email : "Logged Out" --}}

          @yield('content')

          @include('partials._footer')

        </div>  <!--end of containter-->

          @include('partials._scripts')

          @yield('scripts')
           <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
  </body>
</html>
