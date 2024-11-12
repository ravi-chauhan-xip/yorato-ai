@extends('member.layouts.master')

@section('title','Invoice')

@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Invoice</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header-right text-md-right col-md-3 col-12">
            <div class="form-group breadcrumb-right">
                <a class="btn btn-primary mb-75 d-print-none" href="javascript:window.print()">
                    Print
                </a>
            </div>
        </div>
    </div>
    <section class="invoice-preview-wrapper">
        <div class="invoice-preview">
            @include('member.invoice.topup-invoice-html')
            <div class="text-center my-3">
                <a class="btn btn-primary mb-75 d-print-none" href="javascript:window.print()">
                    Print
                </a>
            </div>

        </div>
    </section>
@endsection
