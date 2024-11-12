@extends('admin.layouts.master')

@section('title')
    Edit Member Details
@endsection

@section('content')
    @include('admin.breadcrumbs', [
       'crumbs' => [
           'Edit Member Details'
       ]
   ])
    <div class="row">
        <div class="col-lg-6">
            <form method="post" action="{{ route('admin.users.update', $member) }}">
                @csrf
                @method('patch')
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-primary mb-3"> {{ $member->user->name }} <br>
                            User Wallet Address : {{ $member->user->wallet_address }}</h5>
                        <div class="form-group mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email" id="email"
                                       value="{{ old('email', $member->user->email) }}"
                                       class="form-control"
                                       placeholder="Enter Email ID" required>
                                <label for="email" class="required">Email ID</label>
                            </div>
                            @foreach($errors->get('email') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="uil uil-message me-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
