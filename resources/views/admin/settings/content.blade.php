@extends('admin.layouts.master')
@section('title','Website Content')

@section('content')
    @include('admin.breadcrumbs', [
        'crumbs' => [
            'Website Content'
        ]
   ])
    <div class="row match-height">

        <div class="col-lg-6">
            <form method="post" action="{{ route('admin.settings.about-us') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <label for="about_us">About Us</label>
                        <textarea id="about_us" name="about_us" class="summernote-editor">{{$about_us}}</textarea>
                        @foreach($errors->get('about_us') as $error)
                            <span class="text-danger">{{ $error }}</span>
                        @endforeach
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="uil uil-message me-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6">

            <form method="post" action="{{ route('admin.settings.terms') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <label for="terms">Terms & Conditions</label>
                        <textarea id="terms" name="terms" class="summernote-editor" required>{{$terms}}</textarea>
                        @foreach($errors->get('terms') as $error)
                            <span class="text-danger">{{ $error }}</span>
                        @endforeach
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="uil uil-message me-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-6">

            <form method="post" action="{{ route('admin.settings.privacy') }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <label for="privacy_policy">Privacy Policy</label>
                        <textarea name="privacy_policy" id="privacy_policy"
                                  class="summernote-editor">{{ $privacy_policy }}</textarea>

                        @foreach($errors->get('privacy_policy') as $error)
                            <span class="text-danger">{{ $error }}</span>
                        @endforeach

                        <div class="text-sm-center">
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="uil uil-message me-1"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

{{--        <div class="col-lg-6">--}}

{{--            <form method="post" action="{{ route('admin.settings.vision') }}">--}}
{{--                @csrf--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <label for="vision">Vision</label>--}}
{{--                        <textarea name="vision" id="vision"--}}
{{--                                  class="summernote-editor">{{ $vision_mission }}</textarea>--}}

{{--                        @foreach($errors->get('vision') as $error)--}}
{{--                            <span class="text-danger">{{ $error }}</span>--}}
{{--                        @endforeach--}}

{{--                        <div class="text-sm-center">--}}
{{--                            <button type="submit" class="btn btn-primary mt-3">--}}
{{--                                <i class="uil uil-message me-1"></i> Submit--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}

{{--        <div class="col-lg-6">--}}

{{--            <form method="post" action="{{ route('admin.settings.mission') }}">--}}
{{--                @csrf--}}
{{--                <div class="card">--}}
{{--                    <div class="card-body">--}}
{{--                        <label for="mission">Mission</label>--}}
{{--                        <textarea name="mission" id="mission"--}}
{{--                                  class="summernote-editor">{{ $mission }}</textarea>--}}

{{--                        @foreach($errors->get('mission') as $error)--}}
{{--                            <span class="text-danger">{{ $error }}</span>--}}
{{--                        @endforeach--}}

{{--                        <div class="text-sm-center">--}}
{{--                            <button type="submit" class="btn btn-primary mt-3">--}}
{{--                                <i class="uil uil-message me-1"></i> Submit--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
    </div>
@endsection

@push('page-css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('page-javascript')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            $(".summernote-editor").summernote({
                tabsize: 2,
                height: 250,
                focus: !1,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    // ['fontsize', ['fontsize']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['height', ['height']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endpush
