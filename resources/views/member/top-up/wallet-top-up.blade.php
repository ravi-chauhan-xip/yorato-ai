@extends('member.layouts.master')

@section('title')
    Topup
@endsection

@section('content')
    @include('member.breadcrumbs', [
    'crumbTitle' => function (){
          return 'Topup By Wallet';
        },
         'crumbs' => [
             'Topup By Wallet'
         ],
    ])
    <form method="post" action="{{ route('user.top-up.wallet-store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center" id="app">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="form-group mt-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" name="walletAddress" class="form-control transferCodeInput"
                                           placeholder="Enter Wallet Address" value="{{old('code')}}" required>
                                    <label for="code" class="required">Wallet Address</label>
                                </div>
                                <div class="text-danger transferName"></div>
                                @foreach($errors->get('walletAddress') as $error)
                                    <div class="text-danger code-error">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group mt-2">
                                <select name="package_id" class="form-control form-select "
                                        aria-label="Default select example">
                                    <option value="" selected>Select Package</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}
                                            ( {{ toHumanReadable($package->amount) }} {{ env('APP_CURRENCY_USDT') }} )
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <span
                                class="text-primary">{{ toHumanReadable(Auth::user()->member->wallet_balance) }} {{ env('APP_CURRENCY') }}</span>
                            <br>
                            <span class="text-danger"> </span>
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" type="submit">
                                    <i class="uil uil-message me-1"></i> Submit
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
