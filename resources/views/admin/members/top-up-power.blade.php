@extends('admin.layouts.master')
@section('title') Add TopUp Power @endsection

@section('content')
    @include('admin.breadcrumbs', [
       'crumbs' => [
           'Add TopUp Power'
       ]
   ])
    <div class="row">
        <div class="col-lg-5">
            <form method="post" action="{{ route('admin.users.topup-power-store',$member) }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-primary mb-3">
                            <button class="btn btn-link p-0" type="button"
                                    data-clipboard-text="{{ $member->user->wallet_address }}"
                                    data-title="Click To Copy" data-toggle="tooltip" data-placement="bottom"
                            >
                                {{ $member->user->wallet_address }}
                            </button>
                        </h5>                        <div class="form-group mb-3">
                            <div class="form-group mb-3">
                                <small class="text-light fw-semibold d-block">Side</small>
                                <div class="form-check form-check-inline mt-1">
                                    <input class="form-check-input" type="radio" name="side" id="male"
                                           value="{{ \App\Models\Member::PARENT_SIDE_LEFT }}"
                                           required {{ old('side') == \App\Models\Member::PARENT_SIDE_LEFT ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Left</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="side" id="female"
                                           value="{{ \App\Models\Member::PARENT_SIDE_RIGHT }}"
                                           required {{ old('side') == \App\Models\Member::PARENT_SIDE_RIGHT ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Right</label>
                                </div>
                            </div>
                            @foreach($errors->get('side') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group mb-3">
                            <label>Add Power <span class="text-danger">*</span></label>
                            <input type="number" name="add_bv" value="{{ old('add_bv') }}"
                                   onkeydown="if(event.key==='.'){event.preventDefault();}"
                                   class="form-control" required
                                   placeholder="Enter Power">
                            @foreach($errors->get('add_bv') as $error)
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
