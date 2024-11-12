@extends('admin.layouts.master')

@section('title')
    Create Stake
@endsection

@section('content')
    @include('admin.breadcrumbs', [
        'crumbs' => [
            'Create Stake'
        ]
    ])

    <form method="post" action="{{ route('admin.stake.store') }}"
          onsubmit="registerButton.disabled = true; return true;">
        @csrf
        <div class="row" id="app">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" name="wallet_address" class="form-control transferCodeInput"
                                           placeholder="Enter Wallet Address" value="{{old('code')}}" required>
                                    <label for="code" class="required">Wallet Address</label>
                                </div>
                                <div class="text-danger transferName"></div>
                                @foreach($errors->get('wallet_address') as $error)
                                    <div class="text-danger code-error">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">{{ env('APP_CURRENCY') }}</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" name="amount" id="amount"
                                               value="{{ old('amount') }}" placeholder="Enter Amount" min="1"
                                               oninput="validateNumber(this);"
                                               oninput="validity.valid||(value='');" required>
                                        <label for="amount" class="required">Amount</label>
                                    </div>
                                    @foreach($errors->get('amount') as $error)
                                        <div class="text-danger code-error">{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="form-group col-md-12 text-center">
                                <button type="submit" name="registerButton" class="btn btn-primary"><i
                                        class="uil uil-message me-1"></i> Submit
                                </button>
                            </div>
                        </div>
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

        $("#code").on('keyup change mouseout', function () {
            getWalletDetail($("#code").val())
        });

        $("#code").bind("paste", function (e) {
            getWalletDetail($("#code").val())
        });

        $("body .transferCodeInput").on('input', function () {
            let code = $(".transferCodeInput").val();
            let el = $('.transferCodeInput');

            if (code.length >= 6) {
                $.ajax({
                    url: route('admin.users.wallet-detail', code),
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        $('.transferName').html('');
                        $('.code-error').html('');
                        if (data) {
                            $('.transferName').html(
                                // '<span class="help-block text-primary font-weight-bold">' + data.walletAddress +'</span><br>' +
                                '<span class="help-block text-primary font-weight-bold">Wallet Balance : {{ env('APP_CURRENCY_USDT') }} ' + data.wallet_balance + '</span>'
                            );
                        }
                    },
                    error: function (jqXHR) {
                        $('.transferName').html('');
                        $('.code-error').html('');
                        if (jqXHR.status === 404) {
                            $('.transferName').html(
                                '<span class="help-block text-danger font-weight-bold">Wallet address not found</span>'
                            );
                        } else {
                            $('.transferName').html(
                                '<span class="help-block text-danger font-weight-bold">Wallet address not found</span>'
                            );
                        }
                    },
                });
            } else {
                $('.transferName').html('');
                $('.code-error').html('');
                if (code.length > 0) {
                    $('.transferName').html(
                        '<span class="help-block transferName text-danger font-weight-bold">Wallet address not found</span>'
                    );
                }
            }
        });
    </script>
@endpush

