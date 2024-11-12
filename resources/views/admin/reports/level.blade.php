@extends('admin.layouts.master')

@section('title')
    Level Report
@endsection

@section('content')
    @include('admin.breadcrumbs', [
                            'crumbs' => [
                                "Level Report"
                            ]
                       ])

    <div class="row">
        <div class="col-12">
            <form method="post" action="{{ route('admin.reports.my-team') }}">
                @csrf
                <div class="row mb-4 d-flex">
                    <div class="col-sm-5 col-12">
                        <div class="form-group mb-2">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="code" name="walletAddress"
                                           class="form-control transferCodeInput"
                                           value="{{ old('walletAddress',$walletAddress) }}" placeholder="Search By Wallet Address"
                                           required>
                                    <label for="code">Wallet Address</label>
                                </div>
                                <span class="input-group-text">
                                    <button type="submit" class="btn btn-sm btn-primary"
                                    >Search</button>
                                    &nbsp;
                                        <a href="{{ route('admin.reports.my-team') }}"
                                           class="btn btn-sm btn-danger">
                                    Reset
                                </a>
                                </span>

                            </div>
                            <div class="text-danger transferName"></div>
                            @foreach($errors->get('walletAddress') as $error)
                                <span class="text-danger validationError">{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(isset($levelDetails))
        @if(!empty($levelDetails) && count($levelDetails) >0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body" dir="ltr">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>
                                            Level
                                        </th>
                                        <th>
                                            Total Members
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($levelDetails as $level)
                                        <tr>
                                            <td>
                                                {{ $level['level'] }}
                                            </td>
                                            <td>
                                                {{ $level['teamCount'] }}
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-sm" target="_blank" href="{{ route('admin.reports.level-detail',[
                                                       'level'=>$level['level'],
                                                       'walletAddress'=>$walletAddress,
                                                       ]) }}">View Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body text-center text-capitalize">
                            <h3>
                                Data not found
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
