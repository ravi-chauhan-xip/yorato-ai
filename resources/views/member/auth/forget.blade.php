<!DOCTYPE html>

<html lang="en" class="light-style  customizer-hide" dir="ltr" data-theme="theme-default"
      data-assets-path="assets/" data-template="horizontal-menu-template">
<head>
    <title>Member Reset Password | {{ settings('company_name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta content="{{ settings('company_name') }}" name="description"/>
    <meta content="{{ settings('company_name') }}" name="author"/>
    <link rel="shortcut icon" href="{{ settings()->getFileUrl('favicon', asset(env('FAVICON'))) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @yield('import-css')
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core.css') }}"
          class="template-customizer-core-css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme-default.css') }}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/node-waves.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/page-auth.css') }}"/>
    <script src="{{ asset('assets/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        :root {
            --primary: {{ settings('primary_color') }};
            --primary-hover: {{ settings('primary_color_hover') }};
        }
    </style>
    @yield('page-css')
</head>
<body>
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <div class="card p-2">
                <div class="app-brand justify-content-center mt-5">
                    <a href="{{ env('APP_URL') }}" class="brand-logo app-brand-link gap-2">
                        <img src="{{ settings()->getFileUrl('logo', asset(env('LOGO'))) }}" alt="Logo" title="Logo">
                    </a>
                </div>
                <div class="card-body mt-2">
                    <h4 class="mb-2 fw-semibold">Reset Password?</h4>
                    <p class="mb-5">Enter your Member ID, and we will send you a new password</p>

                    <form id="formAuthentication" class="mb-3" action="{{ route('user.forgot-password.store') }}"
                          method="POST" onsubmit="submit.disabled = true; return true;">
                        @csrf
                        <div class="form-group mb-3">
                            <div class="form-floating form-floating-outline">
                                <input id="code" class="form-control" type="text" placeholder="Enter Member ID"
                                       name="member_code" value="{{ old('member_code') }}" autocomplete="off" required>
                                <label for="code" class="required">Member ID</label>
                            </div>
                            @foreach($errors->get('member_code') as $error)
                                <div class="text-danger font-weight-bold">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit" name="submit">
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <div class="divider my-4">
                        <div class="divider-text">or</div>
                    </div>

                    <p class="text-center">
                        <span>Back to </span>
                        <a href="{{ route('user.login.create') }}">
                            <span>Log In</span>
                        </a>
                    </p>
                </div>
            </div>
            <img alt="mask"
                 src="{{ settings()->getFileUrl('member_background', asset('images/auth-basic-login-mask-light.png')) }}"
                 class="authentication-image d-none d-lg-block">
        </div>
    </div>
</div>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/popper.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/js/node-waves.js') }}"></script>
<script src="{{ asset('assets/js/hammer.js') }}"></script>
<script src="{{ asset('assets/js/i18n.js') }}"></script>
<script src="{{ asset('assets/js/typeahead.js') }}"></script>
<script src="{{ asset('assets/js/menu.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
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
</body>
</html>
