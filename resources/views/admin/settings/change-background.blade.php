@extends('admin.layouts.master')
@section('title')
    Login Background
@endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Login Background'
         ]
    ])
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.settings.change-background') }}" class="filePondForm">
                        @csrf
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between">
                                <label>
                                    Admin Login Background
                                    <small class="text-danger">(Width: 1920px X Height: 1200px)</small>
                                </label>
                            </div>
                            <input type="file" name="admin_background" class="filePondInput mb-0"
                                   value="{{ settings()->getFileUrl('admin_background') }}"
                                   accept="image/*">
                            @foreach($errors->get('admin_background') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label>
                                    Member Login Background
                                    <small class="text-danger">(Width: 1920px X Height: 1200px)</small>
                                </label>
                            </div>
                            <input type="file" name="member_background" class="filePondInput mb-0"
                                   value="{{ settings()->getFileUrl('member_background') }}"
                                   accept="image/*">
                            @foreach($errors->get('member_background') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                <i class="uil uil-message me-1"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script src="{{ asset('assets/js/vapor.min.js') }}"></script>
    <script src="{{ asset('assets/js/filepond.min.js') }}"></script>
@endpush


