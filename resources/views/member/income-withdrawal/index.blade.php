@extends('member.layouts.master')

@section('title') Withdrawals @endsection

@section('content')
    @include('member.breadcrumbs', [
         'crumbs' => [
             'Withdrawals'
         ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush
