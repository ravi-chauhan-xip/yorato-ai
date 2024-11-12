<!DOCTYPE html>
<html lang="en" class="@if(Auth::user()->isDarKTheme()) dark-style @else light-style @endif layout-navbar-fixed layout-menu-fixed " dir="ltr" data-theme="theme-default"
      data-assets-path="/" data-template="horizontal-menu-template">
<head>
    <title>@yield('title') | {{ settings('company_name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta content="{{ settings('company_name') }}" name="description"/>
    <meta content="{{ settings('company_name') }}" name="author"/>
    <link rel="shortcut icon" href="{{ settings()->getFileUrl('favicon', asset(env('FAVICON'))) }}">
    @stack('import-css')
    <link rel="stylesheet" href="{{ asset('member-assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.18/sweetalert2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.4/flatpickr.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('member-assets/vendor/owl-carousel/owl.carousel.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.bootstrap5.css') }}"/>
    <link rel="stylesheet" href="{{ asset('member-assets/css/style.css') }}" class="main-css"  >
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
{{--    <style>--}}
{{--        :root {--}}
{{--            --primary: {{ settings('primary_color') }};--}}
{{--            --primary-hover: {{ settings('primary_color_hover') }};--}}
{{--        }--}}
{{--    </style>--}}
    @stack('page-css')
</head>
<body>
<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<div id="main-wrapper">
    @include('member.layouts.header')
    @include('member.layouts.sidebar')
    <div class="content-body">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
    <div class="footer">
        <div class="copyright">
            <div
                class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                    © Copyright {{ date('Y') }}. All Rights Reserved by
                    <a href="{{ env('APP_URL') }}" target="_blank">{{ settings('company_name') }}</a>
                </div>
                <div>
                    Last Login :
                    @if($lastLoginLog)
                        {{ $lastLoginLog->created_at->dateTimeFormat() }} from <span
                            class="text-primary">{{ $lastLoginLog->ip }}</span>
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stack('import-javascript')
@routes
<script src="{{ asset('member-assets/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('member-assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.4/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.18/sweetalert2.all.js"></script>
<script src="{{ asset('assets/js/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('member-assets/js/custom.js') }}"></script>
<script src="{{ asset('member-assets/js/deznav-init.js') }}"></script>
<script src="{{ asset('member-assets/js/demo.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
@if(Session::has('success'))
    <script>
        Swal.fire('Yay!!!', '{{ Session::get('success') }}', 'success')
    </script>
@endif
@if(Session::has('success-export'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Yay!!!',
            text: '{{ Session::get('success-export') }}',
            footer: '<a href="{{ route('user.exports.index') }}" class="text-primary">Go To Exports</a>'
        });
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
