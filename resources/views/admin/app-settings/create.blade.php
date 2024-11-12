@extends('admin.layouts.master')
@section('title')
    App Settings
@endsection
@section('content')
    @include('admin.breadcrumbs', [
       'crumbs' => [
           'App Settings'
       ]
   ])
    <div class="row">
        <div class="col-lg-4">
            <form action="{{ route('admin.app-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <h5 class="card-header">App Maintenance</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 mb-3">
                                <label for="title" class="form-label required">Maintenance Mode</label> <br>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="androidMaintenance"
                                           id="android_maintenance_yes" value="1"
                                           {{ request('android.maintenance') == '1' || settings('android.maintenance') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="android_maintenance_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="androidMaintenance"
                                           id="android_hard_update_no" value="0"
                                           {{ request('android.maintenance') == '0' || !settings('android.maintenance') ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="android_hard_update_no">No</label>
                                </div>
                                @foreach($errors->get('androidMaintenance') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-12 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" placeholder="Enter Message"
                                           id="maintenanceMessage"
                                           name="maintenanceMessage" required
                                           value="{{ old('maintenanceMessage',settings('android.maintenanceMessage')) }}">
                                    <label for="maintenanceMessage" class="required">Message</label>
                                </div>
                                @foreach($errors->get('maintenanceMessage') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group mt-4 text-center">
                                    <a href="" class="btn btn-danger me-2">
                                        <i class="uil uil-sync"></i>
                                        Reset
                                    </a>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="uil uil-message"></i>
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4">
            <form action="{{ route('admin.app-settings.apk-upload') }}" method="POST"
                  class="filePondForm">
                @csrf
                <div class="card">
                    <h5 class="card-header">App Settings</h5>
                    <div class="card-body">
                        <div class="form-group col-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text" placeholder="Enter Android Version" id="Version"
                                       name="androidVersion" required
                                       value="{{ old('androidVersion',settings('android.version')) }}">
                                <label for="Version" class="required">Android Version</label>
                            </div>
                            @foreach($errors->get('androidVersion') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="title" class="form-label required">Android Update</label> <br>
                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input" type="radio" name="androidHardUpdate"
                                       id="android_hard_update_yes" value="1"
                                       {{ request('android.hardUpdate') == '1' || settings('android.hardUpdate') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="android_hard_update_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="androidHardUpdate"
                                       id="android_hard_update_no" value="0"
                                       {{ request('android.hardUpdate') == '0' || !settings('android.hardUpdate') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="android_hard_update_no">No</label>
                            </div>

                            @foreach($errors->get('androidHardUpdate') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="title" class="form-label required">Web Update</label> <br>

                            <div class="form-check form-check-inline mt-1">
                                <input class="form-check-input" type="radio" name="webHardUpdate"
                                       id="web_hard_update_yes" value="1"
                                       {{ request('web.hardUpdate') == '1' || settings('web.hardUpdate') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="web_hard_update_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="webHardUpdate"
                                       id="web_hard_update_no" value="0"
                                       {{ request('web.hardUpdate') == '0' || !settings('web.hardUpdate') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="web_hard_update_no">No</label>
                            </div>
                            @foreach($errors->get('webHardUpdate') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group col-12 mb-3">
                            <label for="image" class="form-label">Upload APK</label>
                            {{--                            <input type="file" placeholder="Select File" id="image"--}}
                            {{--                                   name="androidApkUrl"--}}
                            {{--                                   accept="image/*"--}}
                            {{--                                   class="form-control">--}}
                            <input type="file" id="androidApkUrl" name="androidApkUrl"
                                   accept="application/vnd.android.package-archive" class="filePondInput"/>

                            @foreach($errors->get('androidApkUrl') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        {{--                        @if($androidApkUrl)--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <a class="btn btn-info me-2" href="{{ $androidApkUrl }}">--}}
                        {{--                                    <i class="uil uil-message"></i>--}}
                        {{--                                    Download App--}}
                        {{--                                </a>--}}
                        {{--                            </div>--}}
                        {{--                        @endif--}}

                        <div class="form-group mt-4 text-center">
                            <a href="" class="btn btn-danger me-2">
                                <i class="uil uil-sync"></i>
                                Reset
                            </a>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="uil uil-message"></i>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('page-javascript')
    <script src="{{ asset('js/vapor.min.js') }}"></script>
    <script src="{{ asset('js/filepond.min.js') }}"></script>
@endpush
