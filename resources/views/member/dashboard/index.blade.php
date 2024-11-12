@extends('member.layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-4">
            <div class="row">
                <div class="col-xl-12 col-lg-6 col-sm-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="mb-0 text-black fs-20">My Profile</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="my-profile">
                                    <img src="{{ $member->present()->profileImage() }}" alt="" class="rounded">
                                </div>
                                <h4 class="mt-3 font-w600 text-black mb-0 name-text">{{ $member->user->name }}</h4>
                                <span>
                                 @if($member->status == \App\Models\Member::STATUS_FREE_MEMBER)
                                        Free <i class="uil uil-snowflake text-danger"></i>
                                    @endif
                                    @if($member->status == \App\Models\Member::STATUS_ACTIVE)
                                        Active <i class="uil uil-comment-verify text-success"></i>
                                    @endif
                            </span>
                                <p class="mb-0 mt-2">Join
                                    on {{ date('d M Y', strtotime($member->user->created_at)) }}</p>
                                <p class="mb-0 mt-2">Activation Date
                                    at {{ $member->activated_at ? date('d M Y', strtotime($member->activated_at)) : '---' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($member->status == \App\Models\Member::STATUS_ACTIVE)
            <div class="col-xl-9 col-sm-8">
                <div class="row match-height">
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="form-section text-capitalize">
                                    <i class="uil uil-share"></i> Left Referral Link
                                </h5>
                            </div>
                            <div class="card-body custom-pad">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                           value="{{ $member->present()->referralLinkLeft() }}"
                                           readonly>
                                    <button class="btn btn-outline-primary" type="button"
                                            data-clipboard-text="{{ $member->present()->referralLinkLeft() }}">
                                        <i class='bx bxs-copy'></i>
                                    </button>
                                </div>

                                <div class="social">
                                    <span class="title">Share This Link:</span>
                                    {!! $socialMediaLinksLeft !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="form-section text-capitalize">
                                    <i class="uil uil-share"></i> Right Referral Link
                                </h5>
                            </div>
                            <div class="card-body custom-pad">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                           value="{{ $member->present()->referralLinkRight() }}"
                                           readonly>
                                    <button class="btn btn-outline-primary" type="button"
                                            data-clipboard-text="{{ $member->present()->referralLinkRight() }}">
                                        <i class='bx bxs-copy'></i>
                                    </button>
                                </div>

                                <div class="social">
                                    <span class="title">Share This Link:</span>
                                    {!! $socialMediaLinksRight !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-sm-6 m-t35">
                        <a href="{{ route('user.wallet-transactions.index') }}">
                            <div class="card card-coin">
                                <div class="card-body text-center">
                                    <img class="img-fluid mb-3 currency-icon" src="{{ asset('images/icons/wallet.png') }}" alt="">
                                    <h2 class="text-black mb-2 font-w600">{{ toHumanReadable(Auth::user()->member->wallet_balance) }}</h2>
                                    <p class="mb-0 fs-14">
                                        Income Wallet ({{env('APP_CURRENCY_USDT')}})
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 col-sm-6 m-t35">
                        <a href="{{ route('user.reports.direct') }}">
                            <div class="card card-coin">
                                <div class="card-body text-center">
                                    <img class="img-fluid mb-3 currency-icon" src="{{ asset('images/icons/direct.png') }}" alt="">
                                    <h2 class="text-black mb-2 font-w600">{{ $myDirects  }}</h2>
                                    <p class="mb-0 fs-13">
                                        My Directs
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 col-sm-6 m-t35">
                        <a href="{{ route('user.reports.downline') }}">
                            <div class="card card-coin">
                                <div class="card-body text-center">
                                    <img class="img-fluid mb-3 currency-icon" src="{{ asset('images/icons/downline.png') }}" alt="">
                                    <h2 class="text-black mb-2 font-w600">{{ $myDownLine  }}</h2>
                                    <p class="mb-0 fs-14">
                                        My Downline
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 col-sm-6 m-t35">
                        <a href="{{ route('user.wallet-transactions.index') }}">
                            <div class="card card-coin">
                                <div class="card-body text-center">
                                    <img class="img-fluid mb-3 currency-icon" src="{{ asset('images/icons/earning.png') }}" alt="">
                                    <h2 class="text-black mb-2 font-w600">{{ $totalEarning  }}</h2>
                                    <p class="mb-0 fs-14">
                                        Total Earning ({{env('APP_CURRENCY_USDT')}})
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-4 col-sm-6 m-t35">
{{--                        <a href="{{route('user.income-withdrawals.index')}}">--}}
                            <div class="card card-coin">
                                <div class="card-body text-center">
                                    <img class="img-fluid mb-3 currency-icon" src="{{ asset('images/icons/today.png') }}" alt="">
                                    <h2 class="text-black mb-2 font-w600">{{ $todayTotalEarning  }}</h2>
                                    <p class="mb-0 fs-14">
                                        Today Income ({{ env('APP_CURRENCY') }})
                                    </p>
                                </div>
                            </div>
{{--                        </a>--}}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
