@extends('member.layouts.master')

@section('title','My Profile')

@section('content')
    @include('member.breadcrumbs', [
          'crumbs' => [
              'My Profile'
          ]
     ])
    <div class="row">
        <div class="col-lg-3">
            <form action="{{route('user.profile.update')}}" method="post" class="filePondForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 form-group mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input id="wallet_address" type="text" name="wallet_address" class="form-control"
                                           placeholder="Enter Name"
                                           value="{{ old('wallet_address', $member->user->wallet_address) }}" readonly>
                                    <label for="name">Wallet Address</label>
                                </div>
                            </div>

{{--                            <div class="col-12">--}}
{{--                                <div class="text-center">--}}
{{--                                    <button type="submit" name="profile" class="btn btn-primary">--}}
{{--                                        <i class="uil uil-message me-1"></i> Submit--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script src="{{ asset('assets/js/vapor.min.js') }}"></script>
    <script src="{{ asset('assets/js/filepond.min.js') }}"></script>
@endpush
