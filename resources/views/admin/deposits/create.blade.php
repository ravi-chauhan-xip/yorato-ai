@extends('admin.layouts.master')
@section('title','Create Manual Deposit')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create Manual Deposit</li>
                    </ol>
                </div>
                <h4 class="page-title">Create Manual Deposit </h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.deposits.store') }}" method="POST">
                        @csrf
                        <div class="ribbon-content">
                            <div class="form-group">
                                <label for="txHash" class="form-label required">Tx Hash</label>
                                <input class="form-control" type="text" placeholder="Enter Tx Hash" required
                                       id="txHash" name="tx_hash"
                                       value="{{ old('tx_hash') }}">
                                @foreach($errors->get('tx_hash') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>

                            <div class="col-12 mt-2 text-center">
                                <a href="{{ route('admin.deposits.index') }}" class="btn btn-danger me-2">
                                    <i class="uil uil-sign-out-alt"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="uil uil-message"></i>
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
