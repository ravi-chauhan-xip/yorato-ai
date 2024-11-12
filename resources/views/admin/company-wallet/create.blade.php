@extends('admin.layouts.master')
@section('title','Create Company Wallet Address')
@section('content')
    @include('admin.breadcrumbs', [
        'crumbs' => [
            'Create Company Wallet Address'
        ]
    ])
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.company-wallet.store') }}" method="POST">
                        @csrf
                        <div class="ribbon-content">
                            <div class="form-group">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="Enter Name"
                                           required
                                           value="{{ old('name') }}">
                                    <label for="name" class="required">Name</label>
                                </div>
                                @foreach($errors->get('name') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="address" id="address"
                                           required
                                           placeholder="Enter Wallet Address"
                                           value="{{ old('address') }}">
                                    <label for="name" class="required">Wallet Address</label>
                                </div>
                                @foreach($errors->get('address') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="privateKey" id="privateKey"
                                           required
                                           placeholder="Enter Private Key"
                                           value="{{ old('privateKey') }}">
                                    <label for="privateKey" class="required">Private Key</label>
                                </div>
                                @foreach($errors->get('privateKey') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="col-12 mt-2 text-center">
                                <a href="{{ route('admin.company-wallet.index') }}" class="btn btn-danger me-2">
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
