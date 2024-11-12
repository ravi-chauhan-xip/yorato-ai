<!DOCTYPE html>

<html lang="en" class="light-style  customizer-hide" dir="ltr" data-theme="theme-bordered"
      data-assets-path="../../assets/" data-template="vertical-menu-template">
<head>
    <title>Login | {{ settings('company_name') }}</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/core.css') }}"
          class="template-customizer-core-css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme-bordered.css') }}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" type="text/css"href="{{ asset('assets/css/page-auth.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
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
                    <h4 class="mb-2 fw-semibold">Welcome to {{ settings('company_name') }}! 👋</h4>
                    <p class="mb-5">Please log in to your account and start the adventure</p>

                    <form class="mb-3" action="{{ route('admin.login.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <div class="form-floating form-floating-outline">
                                <input id="Email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                                       placeholder="Enter Email ID" required>
                                <label for="Email" class="required">Email ID</label>
                            </div>
                            @foreach($errors->get('email') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input id="password-field" type="password" class="form-control" name="password" placeholder="Enter Password"
                                               required>
                                        <label for="password-field" class="required">Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer">
                                        <i class="mdi mdi-eye-off-outline"></i>
                                    </span>
                                </div>
                            </div>
                            @foreach($errors->get('password') as $error)
                                <div class="text-danger font-weight-bold">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="mb-3 d-flex justify-content-between">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" name="remember">
                                <label class="form-check-label" for="remember-me">
                                    Remember Me
                                </label>
                            </div>
                            <a href="{{ route('admin.forgot-password.create') }}" class="float-end mb-1">
                                <span>Forgot Password?</span>
                            </a>
                        </div>
                        <div class="mb-3">
                            <button class="btn w-100 btn-primary waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Login -->
            <img alt="mask"
                 src="{{ settings()->getFileUrl('admin_background', asset('images/Background.svg')) }}"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@if(Session::has('success'))
    <script>Swal.fire('Yay!!!', '{{ Session::get('success') }}', 'success')</script>
@endif
@if(Session::has('error'))
    <script> Swal.fire('Oops!!!', '{{ Session::get('error') }}', 'error')</script>
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
</body>
</html>

