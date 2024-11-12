@extends('member.layouts.master')

@section('title')
    Stake
@endsection

@section('content')
    @include('member.breadcrumbs', [
     'crumbTitle' => function (){
          return 'Stake';
        },
         'crumbs' => [
             'Stake'
         ]
    ])

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
                                <div class="form-group mb-3 mt-2">
                                    <label for="amount">Amount<span class="required"></span></label>
                                    <input type="text" name="amount" class="form-control"
                                           id="amount"
                                           v-model="amount"
                                           placeholder="Enter Amount"
                                           oninput="validateNumber(this);"
                                           oninput="validity.valid||(value='');"
                                           {{--                                                   value="{{old('amount')}}"--}}
                                           required>
                                </div>
                                <span
                                    class="text-primary">@{{ bigIntToEther(web3WalletBalance) }} {{ env('APP_CURRENCY') }}</span>
                                <br>
                                <span class="text-danger"
                                      v-if="stakeCoinValidationError && stakeCoinValidationError.errors.amount">@{{ stakeCoinValidationError.errors.amount[0] }}</span>
                                <div class="text-center">
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
    @vite('resources/js/stake.js')

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
                url: route('user.stake.calculation'),
                data: {amount: amount},
                async: false,
                dataType: 'json',
                success: function (data) {
                    $('.dollarAmount').html('');
                    $('.code-error').html('');
                    if (data && data.dollarAmount > 0) {
                        $('.dollarAmount').html(
                            '<span class="help-block text-primary font-weight-bold">Dollar Amount : ' + data.dollarAmount + '</span>');
                    }
                },
                error: function (jqXHR) {
                    $('.dollarAmount').html('');
                    $('.code-error').html('');
                    $('.dollarAmount').html(
                        '<span class="help-block text-danger font-weight-bold">Something went wrong, please try again</span>'
                    );
                },
            });

        });
    </script>
@endpush
