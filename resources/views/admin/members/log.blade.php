@extends('admin.layouts.master')

@section('title') Member Status Log @endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mt-2">
                <div class="card-header bg-dark py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#filters" role="button" aria-expanded="false"
                           aria-controls="filters">
                            <i class="mdi mdi-minus"></i>
                        </a>
                    </div>
                    <h5 class="card-title mb-0 text-white">Member Status Log of {{$member->user->name}} ({{$member->code}})</h5>
                </div>
                <div id="filters" class="collapse show">
                    <div class="card-body">
                        <form action="{{ route('admin.users.log',$member) }}" id="filterForm">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>From Date</label>
                                    <input type="date" name="joining_from_date" class="form-control"
                                           placeholder="Date">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>To Date</label>
                                    <input type="date" name="joining_to_date" class="form-control"
                                           placeholder="Date">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Admin Name</label>
                                    <input type="text" name="user.name" class="form-control" placeholder="Admin Name">
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>Last Status</label>
                                    <select name="last_status" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($statuses as $value => $status)
                                            <option
                                                value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6 col-md-3 col-lg-2">
                                    <label>New Status</label>
                                    <select name="new_status" class="form-control">
                                        <option value="">Select</option>
                                        @foreach($statuses as $value => $status)
                                            <option
                                                value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('admin.users.log',$member) }}"
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
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="membersTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Admin Name</th>
                                <th>Last Status</th>
                                <th>New Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page-javascript')
    <script>
        var dataTable = $('#membersTable').DataTable({
            ajax: {
                url: '{{ route('admin.users.log',$member) }}',
            },
            "columns": [
                {data: 'DT_RowIndex', width: '5%'},
                {name: "created_at", data: "created_at"},
                {name: "adminName", data: "adminName"},
                {name: "lastStatus", data: "lastStatus"},
                {name: "newStatus", data: "newStatus"},
            ]
        });
    </script>
@endpush
