@extends('member.layouts.master')

@section('title')
    Swap
@endsection

@section('content')
    @include('member.breadcrumbs', [
                    'crumbs' => [
                        'Swap'
                    ]
               ])
    <form method="post" action="{{route('user.buy-coins.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="section mt-4">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="form-group basic p-0">
                                <div class="exchange-heading">
                                    <label class="text-primary " for="fromAmount">You Pay</label>
                                    <div class="exchange-wallet-info">
                                       Income Wallet Balance :
                                        <strong class="text-primary" >
                                            {{ toHumanReadable(Auth::user()->member->wallet_balance)." ".env('APP_CURRENCY_USDT') }}
                                        </strong>
                                    </div>
                                </div>
                                <div class="exchange-group">
                                    <div class="input-col">
                                        <input id="amount" type="text"
                                               class="form-control form-control-lg pe-0 border-0" name="amount"
                                               value="{{ old('amount') }}" placeholder="Enter Amount (100, 200, 300,...)"
                                               oninput="validateNumber(this);"
                                               oninput="validity.valid||(value='');"
                                               required>
                                    </div>
                                    <div class="select-col">
                                        <h4 class="text-dark mb-0">{{env('APP_CURRENCY_USDT') }}</h4>
                                    </div>
                                </div>
                                @foreach($errors->get('amount') as $error)
                                    <span class="text-danger code-error">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="exchange-line">
                        <div class="exchange-icon">
                            <i class='bx bx-transfer'></i>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group basic p-0">
                                <div class="exchange-heading">
                                    <label class="text-primary " for="toAmount">You Receive</label>
                                    <div class="exchange-wallet-info">
                                        Treasure Wallet Balance : <strong class="text-primary"> {{ toHumanReadable(Auth::user()->member->treasure_wallet_balance) }} {{ env('APP_CURRENCY')  }}</strong>
                                    </div>
                                </div>
                                <div class="exchange-group">
                                    <div class="input-col">
                                        <input id="receive_coin" type="text"
                                               class="form-control form-control-lg pe-0 border-0" readonly
                                               value="0" placeholder="{{ env('APP_CURRENCY')  }}"
                                               required>
                                    </div>
                                    <div class="select-col">
                                        <h4 class="text-dark mb-0">{{ env('APP_CURRENCY') }}</h4>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="form-button-group text-center">
                    <button class="btn btn-lg w-100 btn-primary" type="submit">
                        <i class="uil uil-message"></i> Submit
                    </button>
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

            if (amount > 0) {
                $.ajax({
                    url: route('user.buy-coins.calculation'),
                    data: {amount: amount},
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        $('.coins').html('');
                        $('.code-error').html('');
                        if (data && data.coins > 0) {
                            $('#receive_coin').val(data.coins)
                            $('.coins').html(
                                '<span class="help-block text-primary font-weight-bold">Coins : ' + data.coins + '</span>');
                        }
                    },
                    error: function (jqXHR) {
                        $('#receive_coin').val(0)
                        $('.coins').html('');
                        $('.code-error').html('');
                        $('.coins').html(
                            '<span class="help-block text-danger font-weight-bold">Something went wrong, please try again</span>'
                        );
                    },
                });
            } else {
                $('.coins').html('');
                $('.code-error').html('');
                $('#receive_coin').val(0)
            }
        });
    </script>
@endpush
