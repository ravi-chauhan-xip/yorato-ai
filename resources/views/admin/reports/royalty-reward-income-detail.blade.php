@extends('admin.layouts.master')

@section('title')
    Royalty Reward  Report
@endsection

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a class="text-muted fw-light" href="{{ route('admin.dashboard') }}">Home</a>
         /</span> Royalty Reward  Report
    </h5>
    <form method="post" action="{{ route('admin.reports.royalty-reward-income-detail') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" name="walletAddress"
                                           class="form-control transferCodeInput"
                                           value="{{ old('walletAddress',$walletAddress) }}" placeholder="Search By Wallet Address"
                                           required>
                                    <label for="code">Wallet Address</label>
                                </div>
                            </div>
                            <div class="text-danger transferName"></div>
                            @foreach($errors->get('walletAddress') as $error)
                                <span class="text-danger validationError">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="col-12">
                            <div class="text-sm-center">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <a href="{{ route('admin.reports.royalty-reward-income-detail') }}"
                                   class="btn btn-danger waves-effect waves-light font-weight-bold">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if(isset($rewardDetails) && !empty($rewardDetails))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Status</th>
                                <th class="text-center">Matching Business ($)</th>
                                <th class="text-center">Income ($)</th>
                                <th class="text-center">Percentage (%)</th>
                            </tr>
                            </thead>
                            <tbody class="pool-table">
                            @foreach($rewardDetails as $rewardDetail)
                                <tr>
                                    @if($rewardDetail['status'] == 2)
                                        <td class="text-center">
                                            <div class="btn btn-success btn-sm">
                                                Completed
                                            </div>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <div class="btn btn-warning btn-sm">
                                                Pending
                                            </div>
                                        </td>
                                    @endif
                                    <td class="text-center">{{ $rewardDetail['matchingBusiness']}}</td>
                                    <td class="text-center">{{ $rewardDetail['income'] ? : "--"}}</td>
                                    <td class="text-center">{{ $rewardDetail['percentage'] ? : "--"}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('page-javascript')
    <script>
        $(document).ready(function () {
            getWalletDetail($(".transferCodeInput").val())
        })

        $(".transferCodeInput").on('keyup change mouseout', function () {
            getWalletDetail($(".transferCodeInput").val())
        });

        $(".transferCodeInput").bind("paste", function (e) {
            getWalletDetail($(".transferCodeInput").val())
        });

        let code = $(".transferCodeInput").val();
        let el = $('.transferCodeInput');

        function getWalletDetail(code) {
            if (code.length >= 30) {
                $.ajax({
                    url: route('admin.users.wallet-detail', code),
                    data: {code: code},
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        $('.transferName').html('');
                        if (data) {
                            $('.validationError').html('');
                            $('.transferName').html(
                                '<span class="help-block text-primary font-weight-bold">' + data.name + '</span><br>'
                            );
                        }
                    },
                    error: function (jqXHR) {
                        $('.transferName').html('');
                        $('.validationError').html('');
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
                if (code.length > 30) {
                    $('.validationError').html('');
                    $('.transferName').html(
                        '<span class="help-block transferName text-danger font-weight-bold">Wallet address not found</span>'
                    );
                }
            }
        }
    </script>
@endpush
