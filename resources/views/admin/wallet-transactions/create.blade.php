@extends('admin.layouts.master')

@section('title') Wallet Credit/Debits @endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Wallet Credit/Debits'
         ]
    ])
    <form method="post" action="{{ route('admin.wallet-transactions.store') }}">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-3 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-control" name="type" id="type" data-toggle="select2" required>
                                        <option value="">Select Type</option>
                                        @foreach($types as $value => $type)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="type" class="required">Select Type</label>
                                </div>
                                @foreach($errors->get('type') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" name="code" class="form-control transferCodeInput"
                                           placeholder="Enter Wallet Address" value="{{old('code')}}" required>
                                    <label for="code" class="required">Wallet Address</label>
                                </div>
                                <div class="text-danger transferName"></div>
                                @foreach($errors->get('code') as $error)
                                    <div class="text-danger code-error">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">{{ env('APP_CURRENCY_USDT') }}</span>
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" name="amount" id="amount"
                                               value="{{ old('amount') }}" placeholder="Enter Amount" min="1"
                                               oninput="validateNumber(this);"
                                               oninput="validity.valid||(value='');" required>
                                        <label for="amount" class="required">Amount</label>
                                    </div>
                                </div>
                                @foreach($errors->get('amount') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="comment" id="comment" class="form-control"
                                           placeholder="Enter Comment"
                                           value="{{ old('comment') }}">
                                    <label for="comment" class="">Comment</label>
                                </div>
                                @foreach($errors->get('comment') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="submit">
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Admin Credit/Debit</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-hover table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>User Wallet Address</th>
                                <th>Opening Balance ({{ env('APP_CURRENCY_USDT') }})</th>
                                <th>Type</th>
                                <th>Amount ({{ env('APP_CURRENCY_USDT') }})</th>
                                <th>Closing Balance ({{ env('APP_CURRENCY_USDT') }})</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recentTransactions as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transaction->created_at->dateTimeFormat() }}</td>
                                    <td>
                                        {{ view('admin.web3-address',['address' => $transaction->member->user->wallet_address]) }}
                                    </td>
                                    <td>{{ toHumanReadable($transaction->present()->openingBalance()) }}</td>
                                    <td>
                                        @include('admin.wallet-transactions.datatable.type', ['model' => $transaction])
                                    </td>
                                    <td>{{ toHumanReadable($transaction->present()->total()) }}</td>
                                    <td>{{ toHumanReadable($transaction->present()->closingBalance()) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        $("#code").bind("paste", function(e){
            getWalletDetail($("#code").val())
        } );

        $("body .transferCodeInput").on('input', function () {
            let code = $(".transferCodeInput").val();
            let el = $('.transferCodeInput');

            if (code.length >= 6) {
                $.ajax({
                    url: route('admin.users.wallet-detail',code),
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
