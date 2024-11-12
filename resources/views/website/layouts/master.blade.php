<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="{{ settings('primary_color') }}">
    <meta name="msapplication-TileColor" content="{{ settings('primary_color') }}">
    <link rel="shortcut icon" href="{{ settings()->getFileUrl('favicon', asset(env('FAVICON'))) }}" type="image/x-icon">
    @stack('import-css')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/css/select2.min.css"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aos.css') }}">
    @stack('page-css')
</head>
<body>
<div class="cursor"></div>
<div class="cursor cursor--small"></div>
<main>
@include('website.layouts.header')
@yield('content')
</main>
@include('website.layouts.footer')
@routes
@stack('import-javascript')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/click-scroll.js') }}"></script>
<script src="{{ asset('js/aos.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.1/js/select2.min.js"></script>
<script>
    $('[data-toggle="select2"]').select2();
</script>
@if(env('APP_ENV') == 'production')
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
@else
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
@endif
@if(Session::has('success'))
    <script>
        Swal.fire('Yay!!!', '{{ Session::get('success') }}', 'success')
    </script>
@endif

@if(Session::has('error'))
    <script>
        Swal.fire('Oops!!!', '{{ Session::get('error') }}', 'error')
    </script>
@endif
@stack('page-javascript')
</body>
</html>
