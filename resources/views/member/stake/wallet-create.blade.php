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

    <form method="post" action="{{ route('user.stake.wallet-store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center" id="app">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12" v-else>
                                <div class="form-group mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="code" name="walletAddress"
                                               class="form-control transferCodeInput"
                                               placeholder="Enter Wallet Address" value="{{old('code')}}" required>
                                        <label for="code" class="required">Wallet Address</label>
                                    </div>
                                    <div class="text-danger transferName"></div>
                                    @foreach($errors->get('walletAddress') as $error)
                                        <div class="text-danger code-error">{{ $error }}</div>
                                    @endforeach
                                </div>

                                <div class="form-group mb-3 mt-2">
                                    <input type="number" name="amount" class="form-control"
                                           id="amount" pattern="[0-9]*" inputmode="numeric"
                                           placeholder="Enter Amount"
                                           oninput="validateNumber(this);"
                                           oninput="validity.valid||(value='');"
                                           value="{{old('amount')}}"
                                           required>
                                </div>
                                <br>

                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit">
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
