@extends('member.layouts.master')
@section('title') Withdrawal Request @endsection

@section('content')
    @include('member.breadcrumbs', [
         'crumbs' => [
             'Withdrawal create'
         ]
    ])
    <form method="post" action="{{ route('user.withdrawals.store') }}">
        @csrf
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Number of Amount for Withdrawal Request <span class="text-danger">(Wallet Balance : {{ Auth::user()->member->wallet_balance }})</span></label>
                            <input type="text" required oninput="validateNumber(this);"
                                   oninput="validity.valid||(value='');" autocomplete="off" name="amount" class="form-control"
                                   placeholder="Enter Amount " value="{{old('amount')}}">
                            @foreach($errors->get('amount') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="uil uil-message me-1"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
