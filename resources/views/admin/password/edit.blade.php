@extends('admin.layouts.master')

@section('title') Change Password  @endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Change Password'
         ]
    ])
    <div class="row">
        <div class="col-lg-5">
            <form action="{{ route('admin.password.update') }}" method="post">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="form-group col-xl">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control" id="old_password" name="old_password"
                                               required placeholder="Enter Old Password">
                                        <label for="old_password" class="required">Old Password</label>
                                    </div>
                                    <span class="input-group-text cursor-pointer">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </span>
                                </div>
                            </div>
                            @foreach($errors->get('old_password') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="form-group col-xl">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control" id="password" name="password" required
                                                 placeholder="Enter New Password">
                                        <label for="password" class="required">New Password</label>
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
                        <div class="form-group col-xl">
                            <div class="form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control" id="password_confirmation"
                                               name="password_confirmation" required placeholder="Enter Confirm Password">
                                        <label for="password_confirmation" class="required">Confirm Password</label>
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
                        <div class="text-center">
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
