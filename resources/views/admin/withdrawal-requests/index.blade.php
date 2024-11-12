@extends('admin.layouts.master')

@section('title', 'Withdrawal Requests')

@section('content')
    @include('admin.breadcrumbs', [
          'crumbs' => [
              'Withdrawal Requests'
          ]
     ])
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped" id="datatable-buttons">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
