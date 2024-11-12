@extends('member.layouts.master')

@section('title')
    Deposit
@endsection

@section('content')
    @include('member.breadcrumbs', [
             'crumbs' => [
                 'Deposit'
             ]
        ])
    @if(!$status)
    <form method="post" action="{{ route('user.deposits.create') }}">
        @csrf
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label>Amount ({{env('APP_CURRENCY')}})</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" required name="amount" class="form-control"
                                           placeholder="Enter Amount (100, 200, 300, 400,...)"
                                           value="{{ $amount?$amount:'' }}">
                                    @foreach($errors->get('amount') as $error)
                                        <span class="error text-danger memberName">{{ $error }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-sm-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="uil uil-message me-1"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
    @if($status)
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="text-center">
                                <h6 class="text-danger">*Please Make Sure Transfer Only {{ env('APP_CURRENCY') }}
                                    Of {{ settings('network_provider') }}</h6>
                                <h6 class="text-danger">*Deposit Coins to From Wallet Address to Avoid Potential
                                    Loss</h6>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mb-3 mt-2">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" class="form-control"
                                                   placeholder="From Wallet Address"
                                                   value="{{ $userWallet }}" readonly>
                                            <label>From Wallet Address</label>
                                        </div>
                                        <span class="input-group-text">
                                              <button class="btn btn-link text-capitalize p-0 text-normal" type="button"
                                                      data-clipboard-text="{{$userWallet}}"
                                                      data-title="Click To Copy" data-toggle="tooltip"
                                                      data-placement="bottom"
                                              >
                                                <i class='bx bxs-copy'></i>
                                              </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 mt-2">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="deposit_wallet_address" class="form-control"
                                                   id="deposit_address"
                                                   placeholder="Enter Deposit Wallet Address"
                                                   value="{{ $companyDepositWalletAddress }}" readonly>
                                            <label for="deposit_address">Deposit Wallet Address</label>
                                        </div>
                                        <span class="input-group-text">
                                              <button class="btn btn-link text-capitalize p-0 text-normal" type="button"
                                                      data-clipboard-text="{{$companyDepositWalletAddress}}"
                                                      data-title="Click To Copy" data-toggle="tooltip"
                                                      data-placement="bottom"
                                              >
                                                <i class='bx bxs-copy'></i>
                                              </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group mb-3 mt-2">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="deposit_wallet_address" class="form-control"
                                                   id="amount"
                                                   placeholder="Enter Amount"
                                                   value="{{ $amount }}" readonly>
                                            <label for="deposit_address">Amount</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="text-primary text-center">Scan QR Code to Deposit Wallet Address</h6>
                                <div class="text-center d-flex justify-content-center bg-white p-3">
                                    {!! $qrImage !!}
                                </div>
                                <h6 class="text-danger text-center my-3">Please Make Sure Transfer Only  {{ env('APP_CURRENCY') }}
                                    Of {{ settings('network_provider') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
