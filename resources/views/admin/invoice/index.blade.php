@extends('admin.layouts.master')

@section('title','Invoice')

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">
            <a class="text-muted fw-light" href="{{ route('admin.dashboard') }}">Home</a>/
        </span> Invoice
    </h5>
    <div class="row">
        <div class="col-lg-12">
            @include('member.invoice.topup-invoice-html')
        </div>
        <div class="col-lg-12 mb-3 d-print-none">
            <div class="hidden-print mt-2 mb-2 text-center">
                <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light">
                    <i class="uil uil-print"></i> Print
                </a>
            </div>
        </div>
    </div>
@endsection
