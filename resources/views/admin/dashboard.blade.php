@extends('admin.layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    @include('admin.breadcrumbs', [
          'crumbs' => [
              'Dashboard'
          ]
     ])
    <div class="row">
        <div class="col-sm-12 col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-6 col-xl-2 col-6 mb-lg-0 mb-2">
                            <a href="{{ route('admin.users.index') }}">
                                <div class="text-center">
                                    <i class='h1 mdi mdi-account-group text-dark'></i>
                                    <h3 class="mb-1"><span data-plugin="counterup">{{ $totalMembers }}</span></h3>
                                    <p class="text-muted font-15 mb-0">Total Users</p>
                                </div>
                            </a>
                        </div>
                        {{--                        <div class="col-sm-6 col-xl-2 col-6 mb-lg-0 mb-2">--}}
                        {{--                            <a href="{{ route('admin.users.index', ['status' => \App\Models\Member::STATUS_BLOCKED]) }}">--}}
                        {{--                                <div class="text-center">--}}
                        {{--                                    <i class='h1 mdi mdi-account-cancel text-danger'></i>--}}
                        {{--                                    <h3 class="mb-1"><span data-plugin="counterup">{{ $blockedMembers }}</span></h3>--}}
                        {{--                                    <p class="text-muted font-15 mb-0">Blocked Users</p>--}}
                        {{--                                </div>--}}
                        {{--                            </a>--}}
                        {{--                        </div>--}}
                        <div class="col-sm-6 col-xl-2 col-6 mb-lg-0 mb-2">
                            <a href="{{ route('admin.users.index', ['from_activated_at' => today()->format('Y-m-d')]) }}">
                                <div class="text-center">
                                    <i class='h1 mdi mdi-account-star text-warning'></i>
                                    <h3 class="mb-1"><span data-plugin="counterup">{{ $todayActivation }}</span></h3>
                                    <p class="text-muted font-15 mb-0">Today's Activation</p>
                                </div>
                            </a>
                        </div>
{{--                        <div class="col-sm-6 col-xl-2 col-6 mb-lg-0 mb-2">--}}
{{--                            <a href="#">--}}
{{--                                <div class="text-center">--}}
{{--                                    <i class='h1 mdi mdi-account-star text-info'></i>--}}
{{--                                    <h3 class="mb-1"><span data-plugin="counterup">{{ $paidMembers }}</span></h3>--}}
{{--                                    <p class="text-muted font-15 mb-0">Total Paid Users</p>--}}
{{--                                </div>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                        <div class="col-sm-6 col-xl-2 col-6 mb-lg-0 mb-2">
                            <a href="{{ route('admin.users.index', ['status' => \App\Models\Member::STATUS_ACTIVE]) }}">
                                <div class="text-center">
                                    <i class='h1 mdi mdi-account-check text-success'></i>
                                    <h3 class="mb-1"><span data-plugin="counterup">{{ $activeMembers }}</span></h3>
                                    <p class="text-muted font-15 mb-0">Total Active Users</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-2">Overview</h5>
                    </div>
                </div>
                <div class="card-body gap-3">
                    <div class="row">
                        <div class="col-xl-2 col-sm-4 mb-3">
                            <a href="#">
                                <div class="d-flex gap-3">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-dark rounded">
                                            <i class="mdi mdi-trending-up mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h4 class="mb-0">{{ $totalTurnOverUSDT }}</h4>
                                        <small class="text-muted">Total Turnover ({{ env('APP_CURRENCY_USDT') }}
                                            )</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-sm-4 mb-3">
                            <a href="#">
                                <div class="d-flex gap-3">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-dark rounded">
                                            <i class="mdi mdi-trending-up mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h4 class="mb-0">{{ $totalPreviousMonthTurnOverUSDT }}</h4>
                                        <small class="text-muted">Previous Month Turnover
                                            <br>({{ env('APP_CURRENCY_USDT') }})</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-sm-4 mb-3">
                            <a href="#">
                                <div class="d-flex gap-3">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-dark rounded">
                                            <i class="mdi mdi-trending-up mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h4 class="mb-0">{{ $totalCurrentMonthTurnOverUSDT }}</h4>
                                        <small class="text-muted">Current Month Turnover ({{ env('APP_CURRENCY_USDT') }}
                                            )</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-sm-4 mb-3">
                            <a href="{{ route('admin.stake.index') }}">
                                <div class="d-flex gap-3">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-dark rounded">
                                            <i class="mdi mdi-swap-horizontal-circle mdi-24px"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h4 class="mb-0">{{ $totalStake }}</h4>
                                        <small class="text-muted">Total Stake ({{ env('APP_CURRENCY') }})</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-2 col-sm-4 mb-3">
                            <a href="{{route('admin.income-withdrawal-requests.index',['from_created_at' => \Carbon\Carbon::now()->format('Y-m-d'),'to_created_at' => \Carbon\Carbon::now()->format('Y-m-d'),'status' => \App\Models\IncomeWithdrawalRequest::STATUS_COMPLETED])}}">
                                <div class="d-flex gap-3">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-info rounded">
                                            <i class="menu-icon tf-icons mdi mdi-package"></i>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h4 class="mb-0">{{$todayWithdrawals}}</h4>
                                        <small class="text-muted">Today Income Withdrawal <br>
                                            ({{ env('APP_CURRENCY') }})</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 me-2">Last 5 Registration</h5>
                </div>
                <div class="card-body pt-2">
                    <ul class="p-0 m-0">
                        @foreach($lastFiveRegisterMembers as $key => $member)
                            <li class="d-flex mb-3 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ $member->present()->profileImage() }}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ view('admin.web3-address',['address' => $member->user->wallet_address]) }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>{{ $member->created_at->dateFormat() }}</span>
                                        </small>
                                    </div>
                                    @if($member->sponsor)
                                        <div>
                                            {{ view('admin.web3-address',['address' => $member->sponsor ? $member->sponsor->user->wallet_address : '']) }}
                                            <br>
                                            <small class="d-none d-sm-block">Sponsor Address</small>
                                        </div>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 me-2">Last 5 Activation</h5>
                </div>
                <div class="card-body pt-2">
                    <ul class="p-0 m-0">
                        @foreach($lastFiveActivation as $key => $member)
                            <li class="d-flex mb-3 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ $member->present()->profileImage() }}" alt="avatar" class="rounded">
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ view('admin.web3-address',['address' => $member->user->wallet_address]) }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="mdi mdi-calendar-blank-outline mdi-14px"></i>
                                            <span>{{ $member->activated_at->dateFormat() }}</span>
                                        </small>
                                    </div>
                                    <div>
                                        {{ view('admin.web3-address',['address' => $member->sponsor ? $member->sponsor->user->wallet_address : '']) }}
                                        <br>
                                        <small class="d-none d-sm-block">Sponsor Address</small>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body" dir="ltr">
                    <h5 class="header-title mb-3">Company Daily Turnover</h5>
                    <canvas id="dailyTurnOverChart" height="350vw" width="500vw"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body" dir="ltr">
                    <h5 class="header-title mb-3">Last 7 Days Daily Joining</h5>
                    <canvas id="dailyJoining" height="350vw" width="500vw"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script>
        let ctx = document.getElementById('dailyTurnOverChart');

        ctx.height = 350;

        var dailyTurnOverChart = new Chart(ctx, {
            responsive: true,
            type: 'line',
            data: {
                labels: {!! json_encode($dayWisePackageSubscriptions->pluck('day')) !!},
                datasets: [{
                    label: 'Daily Turn Over',
                    data: {!! json_encode($dayWisePackageSubscriptions->pluck('amount')) !!},
                    backgroundColor: 'rgba(20, 150, 252, 0.5)',
                    borderColor: 'rgba(20, 150, 252, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        },
                        ticks: {
                            major: {
                                fontStyle: 'bold',
                                fontColor: '#FF0000'
                            }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            },
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount ({{env('APP_CURRENCY_USDT')}})'
                        }
                    }]
                }
            }
        });

        ctx = document.getElementById('dailyJoining');

        ctx.height = 350;

        var dailyJoining = new Chart(ctx, {
            responsive: true,
            type: 'bar',
            data: {
                labels: {!! json_encode($dayCountMembersJoins->pluck('day')) !!},
                datasets: [{
                    label: 'Last 7 Days Daily Joining',
                    data: {!! json_encode($dayCountMembersJoins->pluck('total_member')) !!},
                    backgroundColor: 'rgba(20, 150, 252, 0.5)',
                    borderColor: 'rgba(20, 150, 252, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        },
                        ticks: {
                            major: {
                                fontStyle: 'bold',
                                fontColor: '#FF0000'
                            }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            },
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Users'
                        }
                    }]
                }
            }
        });
    </script>
@endpush
