@extends('admin.layouts.master')

@section('title')
    Change Password
@endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Change Password'
         ]
     ])
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <h5 class="card-header">
                    Change Password
                    <span class="text-primary me-2">({{ $admin->name }})</span>
                </h5>
                <div class="card-body">
                    <form action="{{route('admin.admins.change-password-update', $admin)}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control" id="password" name="password"
                                                   required
                                                   placeholder="Enter New Password">
                                            <label for="password">New Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </span>
                                    </div>
                                </div>
                                @foreach($errors->get('password') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                   name="password_confirmation" required
                                                   placeholder="Enter Confirm Password">
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </span>
                                    </div>
                                </div>
                                @foreach($errors->get('password_confirmation') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="col-12 mt-1 text-center">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="uil uil-message"></i>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
