@extends('member.layouts.master')
@section('title')
    Withdrawal Request
@endsection

@section('content')
    @include('member.breadcrumbs', [
'crumbTitle' => function (){
  return 'Withdrawal Request';
},
'crumbs' => [
 'Withdrawal Request ',
]
])
    <form method="post" action="{{ route('user.income-withdrawals.store') }}">
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    @if(Auth::user()->member->user->wallet_address)
                    <div class="card-header">
                            <h5 class="text-normal mb-3">Wallet Address <span class="text-primary">{{Auth::user()->member->user->wallet_address}}</span></h5>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Number of Amount for Withdrawal Request <span class="text-danger">(Wallet Balance : {{ toHumanReadable(Auth::user()->member->wallet_balance)." USDT"}})</span></label>
                            <input type="text" required autocomplete="off" name="amount" class="form-control"
                                   id="amount"
                                   oninput="validateNumber(this);"
                                   oninput="validity.valid||(value='');"
                                   placeholder="Enter Amount (USDT)" value="{{old('amount')}}">
                                <div class="text-danger trsAmount"></div>
                                <div class="text-danger serviceCharge"></div>
                                <div class="text-danger totalTRS"></div>

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

    @push('page-javascript')
        <script>
            var validNumber = new RegExp(/^\d*\.?\d*$/);
            var lastValid = document.getElementById("amount").value;

            function validateNumber(elem) {
                if (validNumber.test(elem.value)) {
                    lastValid = elem.value;
                } else {
                    elem.value = lastValid;
                }
            }
        </script>

        <script>
            $("body #amount").on('input', function () {
                let amount = $("#amount").val();
                let el = $('#amount');

                $.ajax({
                    url: route('user.income-withdrawals.calculation'),
                    data: {amount: amount},
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $('.trsAmount').html('');
                        $('.code-error').html('');
                        $('.serviceCharge').html('');
                        $('.totalTRS').html('');
                        if (data && data.trsAmount > 0) {
                            $('.trsAmount').html(
                                '<span class="help-block text-primary font-weight-bold">Amount (USDT) : ' + data.trsAmount + ' </span>');
                        }
                        if (data && data.serviceCharge > 0) {
                            $('.serviceCharge').html(
                                '<span class="help-block text-primary font-weight-bold">Service Charge (USDT) : ' + data.serviceCharge + ' </span>');
                        }
                        if (data && data.totalTRS > 0) {
                            $('.totalTRS').html(
                                '<span class="help-block text-primary font-weight-bold">Total Amount (USDT) : ' + data.totalTRS + ' </span>');
                        }
                    },
                    error: function (jqXHR) {
                        $('.trsAmount').html('');
                        $('.serviceCharge').html('');
                        $('.totalTRS').html('');
                        $('.code-error').html('');
                        $('.trsAmount').html(
                            '<span class="help-block text-danger font-weight-bold">Something went wrong, please try again</span>'
                        );
                    },
                });
            });
        </script>
    @endpush
