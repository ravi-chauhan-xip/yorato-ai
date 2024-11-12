@extends('member.layouts.master')

@section('title')
    Topup
@endsection

@section('content')
    @include('member.breadcrumbs', [
    'crumbTitle' => function (){
          return 'Topup';
        },
         'crumbs' => [
             'Topup'
         ],
    ])
    {{--    <div id="preloader">--}}
    {{--        <div class="sk-three-bounce">--}}
    {{--            <div class="sk-child sk-bounce1"></div>--}}
    {{--            <div class="sk-child sk-bounce2"></div>--}}
    {{--            <div class="sk-child sk-bounce3"></div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <form action="#" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center" id="app">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12" v-if="!connectedWallet || connectedChain.id != chainId">
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
                                                    @click="setProperChain()"
                                                    class="btn w-100 btn-primary">
                                                Switch to @{{ chainName }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12" v-else>
                                <div class="form-group mt-2">
                                    <select v-model="package_id" class="form-control form-select "
                                            aria-label="Default select example"
                                            v-on:change="selectPackage(package_id)">
                                        <option value="" selected>Select Package</option>
                                        <option :value="package.id" v-for="package in packages">@{{
                                            package.name }} ( @{{ package.amount
                                            }} {{ env('APP_CURRENCY_USDT') }})
                                        </option>
                                    </select>
                                </div>
                                <span
                                        class="text-primary">@{{ bigIntToEther(web3WalletBalance) }} {{ env('APP_CURRENCY') }}</span>
                                <br>
                                <span class="text-danger"
                                      v-if="stakeCoinValidationError && stakeCoinValidationError.errors.package_id">@{{ stakeCoinValidationError.errors.package_id[0] }}</span>
                                <div class="text-center mt-3">
                                    <button class="btn btn-primary" @click="stakeCoin" type="submit">
                                        <i class="uil uil-message me-1"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('page-javascript')
    @vite('resources/js/top-up.js')
@endpush
