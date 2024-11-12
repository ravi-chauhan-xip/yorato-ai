@extends('admin.layouts.master')

@section('title')
    Withdrawal Requests
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Withdrawal Requests</h5>
                    <div class="heading-elements">
{{--                        <span class="badge badge-success float-md-left mr-1 py-1 px-1">--}}
{{--                            <span style="color: black">Total Amount : {{ env('APP_CURRENCY') }} <span id="totalGST"></span></span>--}}
{{--                        </span>--}}
                        <a data-bs-toggle="collapse" href="#filters" role="button"
                           aria-expanded="{{ Agent::isMobile() ? 'true' : 'false'}}"
                           aria-controls="filters" class="{{ Agent::isMobile() ? 'collapsed' : ''}}">
                            <i class="uil uil-minus"></i>
                        </a>
                    </div>
                </div>
                <div id="filters" class="collapse {{ Agent::isMobile() ? '' : 'show'}}">
                    <div class="card-body">
                        <form action="{{ route('admin.income-withdrawal-requests.index') }}" id="filterForm">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>From Date</label>
                                    <input type="date" name="createdAtFrom" value="{{request()->get('from_created_at')}}" class="form-control"
                                           placeholder="From Date">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>To Date</label>
                                    <input type="date" name="createdAtTo" value="{{request()->get('to_created_at')}}" class="form-control"
                                           placeholder="To Date">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label  for="example-input-small">Status</label>
                                    <select class="form-control"  name="status" data-toggle="select2">
                                        <option value="">Select Status</option>
                                        @foreach($statuses as $value => $status)
                                            <option
                                                value="{{ $value }}" {{ old('status', request()->get('status')) == $value ? 'selected' : ''}}
                                            >
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label for="example-input-small">Blockchain Status</label>
                                    <select class="form-control"  name="blockchain_status"
                                            data-toggle="select2">
                                        <option value="">Select Blockchain Status</option>
                                        @foreach($blockChainStatus as $value => $status)
                                            <option
                                                value="{{ $value }}" {{ old('blockchain_status', $status) == $value ? 'selected' : ''}}
                                            >
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>User Wallet Address</label>
                                    <input type="text" name="member.user.wallet_address" class="form-control"
                                           placeholder="User Wallet Address">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>From Address</label>
                                    <input type="text" name="from_address" class="form-control"
                                           placeholder="From Address">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>To Address</label>
                                    <input type="text" name="to_address" class="form-control"
                                           placeholder="To Address">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Amount ({{env('APP_CURRENCY')}})</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="min_amount" class="form-control"
                                                   placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="max_amount" class="form-control"
                                                   placeholder="Max">
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="form-group col-sm-6 col-md-3 col-lg-2">--}}
{{--                                    <label>Coin Price</label>--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6">--}}
{{--                                            <input type="text" name="min_coin_price" class="form-control"--}}
{{--                                                   placeholder="Min">--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6">--}}
{{--                                            <input type="text" name="max_coin_price" class="form-control"--}}
{{--                                                   placeholder="Max">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-sm-6 col-md-3 col-lg-2">--}}
{{--                                    <label>Amount ({{env('APP_CURRENCY')}})</label>--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-6">--}}
{{--                                            <input type="text" name="min_coin" class="form-control"--}}
{{--                                                   placeholder="Min">--}}
{{--                                        </div>--}}
{{--                                        <div class="col-6">--}}
{{--                                            <input type="text" name="max_coin" class="form-control"--}}
{{--                                                   placeholder="Max">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Service Charge ({{env('APP_CURRENCY')}})</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="min_service_charge" class="form-control"
                                                   placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="max_service_charge" class="form-control"
                                                   placeholder="Max">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Total ({{env('APP_CURRENCY')}})</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="text" name="min_total" class="form-control"
                                                   placeholder="Min">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" name="min_total" class="form-control"
                                                   placeholder="Max">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Transaction Hash</label>
                                    <input type="text" name="tx_hash" class="form-control"
                                           placeholder="Transaction Hash">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Error</label>
                                    <input type="text" name="error" class="form-control"
                                           placeholder="Error">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('admin.income-withdrawal-requests.index') }}"
                                       class="btn btn-danger waves-effect waves-light font-weight-bold">
                                        Reset
                                    </a>
                                    <button type="submit" name="filter" value="filter" onclick="shouldExport = false;"
                                            class="btn btn-primary waves-effect waves-light font-weight-bold">
                                        Apply Filter
                                    </button>
                                    <button type="submit" name="export" value="csv" onclick="shouldExport = true;"
                                            class="btn btn-secondary waves-effect waves-light font-weight-bold float-right">
                                        Export
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped" id="pinRequestsTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th></th>
                            <th>Date</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Blockchain Status</th>
                            <th>User Wallet Address</th>
                            <th>From Address</th>
                            <th>To Address</th>
                            <th>Amount ({{ env('APP_CURRENCY_USDT') }})</th>
{{--                            <th>Coin Price ({{ env('APP_CURRENCY_USDT') }})</th>--}}
{{--                            <th>Amount ({{ env('APP_CURRENCY') }})</th>--}}
                            <th>Service Charge ({{ env('APP_CURRENCY') }})</th>
                            <th>Total ({{ env('APP_CURRENCY') }})</th>
                            <th>Transaction Hash</th>
                            <th>Error</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script>
        let totalGST = $('#totalGST');
        var dataTable = $('#pinRequestsTable').DataTable({
            ajax: {
                url: '{{ route('admin.income-withdrawal-requests.index') }}',
                dataSrc: function (json) {
                    totalGST.html(json.totalWithdrawal);

                    return json.data;
                }
            },
            "columns": [
                {data: 'DT_RowIndex', width: '5%'},
                {name: "created_at", data: "created_at"},
                {data: 'checkbox', name: 'checkbox', searchable: false, orderable: false},
                {name: "action", data: "action"},
                {name: "status", data: "status"},
                {name: "blockchain_status", data: "blockchain_status"},
                {name: "member.user.walletAddress", data: "walletAddress"},
                {name: "from_address", data: "fromAddress"},
                {name: "to_address", data: "toAddress"},
                {name: "amount", data: "amount"},
                // {name: "coin_price", data: "coin_price"},
                // {name: "coin", data: "coin"},
                {name: "service_charge", data: "service_charge"},
                {name: "total", data: "total"},
                {name: "tx_hash", data: "tx_hash"},
                {name: "error", data: "error"},
            ]
        });
    </script>
@endpush
