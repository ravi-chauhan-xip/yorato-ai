@extends('admin.layouts.master')

@section('title') Change Logo @endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Change Logo'
         ]
    ])
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.settings.change-logo') }}" class="filePondForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Logo <span class="text-danger">*</span> <span class="text-danger"> (Width: 260px x Height: 80px)</span></label>
                            <input type="file" name="logo" class="filePondInput"
                                   value="{{ settings()->getFileUrl('logo') }}"
                                   required accept="image/*">
                            @foreach($errors->get('logo') as $error)
                                <div class="text-danger">{{ $error }}</div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label>Favicon<span class="text-danger">*</span> <span class="text-danger"> (Width: 32px x Height: 32px)</span></label>
                            <input type="file" name="favicon" class="filePondInput"
                                   value="{{ settings()->getFileUrl('favicon') }}"
                                   required accept="image/*">
                            @foreach($errors->get('favicon') as $error)
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

@include('admin.layouts.filepond')


