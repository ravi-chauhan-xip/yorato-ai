<!DOCTYPE html>

<html lang="en" class="light-style  customizer-hide" dir="ltr" data-theme="theme-default"
      data-assets-path="assets/" data-template="horizontal-menu-template">
<head>
    <title>User Register | {{ settings('company_name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta content="{{ settings('company_name') }}" name="description"/>
    <meta content="{{ settings('company_name') }}" name="author"/>
    <link rel="shortcut icon" href="{{ settings()->getFileUrl('favicon', asset(env('FAVICON'))) }}">
    @yield('import-css')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icons.min.css') }}">
    <link class="main-css"  rel="stylesheet" href="{{ asset('member-assets/css/style.css') }}" >
    <style>
        :root {
            --primary: {{ settings('primary_color') }};
            --primary-hover: {{ settings('primary_color_hover') }};
        }
        .brand-logo img {
            max-width: 260px;
        }
    </style>
    @yield('page-css')
</head>
<body class="vh-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content" id="app">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form" >
                                <div class="text-center mb-3">
                                    <a href="{{ env('APP_URL') }}" class="brand-logo app-brand-link gap-2">
                                        <img src="{{ settings()->getFileUrl('logo', asset(env('LOGO'))) }}" alt="Logo" title="Logo">
                                    </a>
                                </div>
                                <h4 class="text-center mb-4">Sign in your account</h4>
                                <div class="card-body mt-2 text-center">
                                    <div v-if="!connectedWallet || connectedChain.id != chainId">
                                        <h4 class="mb-2 fw-semibold">Welcome to {{ settings('company_name') }}! 👋</h4>
                                        <p class="mb-3">Please Connect Wallet and start the adventure</p>
                                        <div v-if="!connectedWallet || connectedChain.id != chainId">
                                            <div v-if="!connectedWallet">
                                                <button type="button" @click="connect()"
                                                        class="btn w-100 btn-primary">
                                                    <span v-if="connectingWallet">Connecting...</span>
                                                    <span v-else>Connect Wallet</span>
                                                </button>
                                            </div>
                                            <div v-else-if="connectedChain.id !== chainId">
                                                <div class="my-3">
                                                    <button type="button"
                                                            @click="setBinanceChain()"
                                                            class="btn w-100 btn-primary">
                                                        Switch to @{{ chainName }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <div v-if="!isUserRegistered">
                                            {{--                            <h4 class="mb-5 fw-semibold">Register</h4>--}}
                                            <form action="#">

                                                <div class="form-group text-center">
                                                    <div class="form-check form-check-inline mt-1">
                                                        <input class="form-check-input" type="radio" name="side" id="left"
                                                               v-model="side" value="1" :disabled="isSideDisable"
                                                               {{--                                               {{ old('side',request()->get('side')) == \App\Models\Member::PARENT_SIDE_LEFT ? 'checked' : '' }}--}}
                                                               required>
                                                        <label class="form-check-label" for="left">Left</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="side" id="right"
                                                               v-model="side" value="2" :disabled="isSideDisable"
                                                               {{--                                               {{ old('side',request()->get('side')) == \App\Models\Member::PARENT_SIDE_RIGHT ? 'checked' : '' }}--}}
                                                               required>
                                                        <label class="form-check-label" for="right">Right</label>
                                                    </div>
                                                    <div class="text-center mb-3">
                                                        <span v-if="sideError" class="text-danger">@{{ sideError }}</span>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4 text-start">
                                                    <div class="form-floating form-floating-outline">
                                                        <input id="referralWalletAddress" class="form-control" type="text"
                                                               placeholder="Enter Referral Wallet Address" name="referralWalletAddress"
                                                               v-model="referralWalletAddress"
                                                               autocomplete="off" required>
                                                        <label for="referralWalletAddress" class="required">Referral Wallet
                                                            Address</label>
                                                    </div>
                                                    <span class="text-danger"
                                                          v-if="registerValidationError && registerValidationError.errors.referralWalletAddress">@{{ registerValidationError.errors.referralWalletAddress[0] }}</span>
                                                </div>

                                                <div class="form-group mb-3 text-start">
                                                    <div class="form-floating form-floating-outline">
                                                        <input id="walletAddress" class="form-control" type="text"
                                                               placeholder="Enter Wallet Address"
                                                               name="walletAddress" readonly
                                                               v-model="walletAddress" autocomplete="off" required>
                                                        <label for="walletAddress" class="required">Wallet Address</label>
                                                    </div>
                                                    <span class="text-danger" v-if="registerValidationError">@{{ registerValidationError.errors.walletAddress ? registerValidationError.errors.walletAddress[0] : ''  }}</span>
                                                </div>
                                                <div class="mb-0 d-flex justify-content-between">
                                                    <div class="form-check">
                                                        <input class="form-check-input" v-model="termsCheckbox" type="checkbox"
                                                               id="remember-me" required>
                                                        <label class="form-check-label" for="remember-me">
                                                            I agree to the
                                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                               data-bs-target=".bs-example-modal-center"><b>Terms & Conditions.</b>
                                                            </a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="text-start mb-3">
                                                    <span v-if="termsError" class="text-danger">@{{ termsError }}</span>
                                                </div>
                                                <div class="mb-3">
                                                    <button class="btn btn-primary w-100" @click="register()" type="submit"
                                                            name="registerButton">
                                                        Register
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div v-else-if="isUserRegistered && walletAddress">
                                            <h4 class="mb-5 fw-semibold">Redirecting To Dashboard....</h4>
                                        </div>
                                        <div v-else>
                                            <h4 class="mb-5 fw-semibold text-danger">Wallet Not found</h4>
                                        </div>
                                    </div>
                                    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
                                         aria-labelledby="myCenterModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="myCenterModalLabel">Terms & Conditions</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(settings('terms'))
                                                        {!! settings('terms') !!}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@routes
<script src="{{ asset('member-assets/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('member-assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"></script>
<script src="{{ asset('member-assets/js/custom.js') }}"></script>
<script src="{{ asset('member-assets/js/deznav-init.js') }}"></script>
<script src="{{ asset('member-assets/js/demo.js') }}"></script>
<script src="{{ asset('member-assets/js/styleSwitcher.js') }}"></script>
@vite('resources/js/register.js')

</body>
</html>
