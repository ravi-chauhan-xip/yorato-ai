@extends('member.layouts.master')

@section('title')
    KYC Details
@endsection

@section('content')
    @include('member.breadcrumbs', [
       'crumbTitle' => function () use ($kyc) {
           if ($kyc->isPending())
               $html = '<span class="text-warning">Pending</span>';
           else if ($kyc->isApproved())
               $html = '<span class="text-success">Approved</span>';
           else if ($kyc->isRejected())
               $html = '<span class="text-danger">Rejected</span>';
           else
               $html = '<span class="text-danger">Not Applied</span>';

           return 'KYC Details : ' . $html;
       },
       'crumbs' => [
          'KYC Details',
       ]
  ])
    <form method="post" action="{{ route('user.kycs.update') }}" class="filePondForm">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title-wrap">
                            <h5 class="card-title">Identity Information</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <div class="form-floating form-floating-outline">
                                <input id="pan" type="text" required name="pan_card" class="form-control"
                                       placeholder="Enter PAN Card"
                                       value="{{ old('pan_card',optional($kyc)->pan_card) }}">
                                <label for="pan">PAN Card <span class="text-danger">*</span></label>
                            </div>
                            @foreach($errors->get('pan_card') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-floating form-floating-outline">
                                <input id="aadhaar" type="text" required name="aadhaar_card" class="form-control"
                                       placeholder="Enter Aadhaar Card"
                                       value="{{old('aadhaar_card',optional($kyc)->aadhaar_card)}}"
                                       onkeydown="return max_length(this,event,12)"
                                       onkeypress="return isNumberKey(event)" pattern=".{12,12}">
                                <label for="aadhaar">Aadhaar Card <span class="text-danger">*</span></label>
                            </div>
                            @foreach($errors->get('aadhaar_card') as $error)
                                <span class="text-danger">{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title-wrap">
                            <h5 class="card-title">Bank Information</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input id="Account" type="text" name="account_name" class="form-control"
                                           placeholder="Enter Account Holder Name"
                                           value="{{old('account_name',optional($kyc)->account_name)}}" required>
                                    <label for="Account">Account Holder Name <span class="text-danger">*</span></label>
                                </div>
                                @foreach($errors->get('account_name') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input id="account_number" type="text" name="account_number" class="form-control"
                                           placeholder="Enter Account Number"
                                           value="{{old('account_number',optional($kyc)->account_number)}}"
                                           onkeydown="return max_length(this,event,20)"
                                           onkeypress="return isNumberKey(event)" required>
                                    <label for="account_number">Account Number <span
                                            class="text-danger">*</span></label>
                                </div>
                                @foreach($errors->get('account_number') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select id="account_type" class="form-select" data-toggle="select2"
                                            name="account_type" required>
                                        <option value="">Choose Account Type</option>
                                        <option value="1" {{optional($kyc)->account_type == 1 ? 'selected' : ''}}>Saving
                                        </option>
                                        <option value="2" {{optional($kyc)->account_type == 2 ? 'selected' : ''}}>
                                            Current
                                        </option>
                                    </select>
                                    <label for="account_type">Account Type</label>
                                </div>
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="bank_ifsc" name="bank_ifsc" class="form-control"
                                           placeholder="Enter IFSC Code"
                                           value="{{ old('bank_ifsc', optional($kyc)->bank_ifsc) }}" required>
                                    <label for="bank_ifsc">IFSC Code <span class="text-danger">*</span></label>
                                    <span class="text-danger" id="bank_ifsc_error"></span>
                                </div>
                                @foreach($errors->get('bank_ifsc') as $error)
                                    <span class="text-danger backendError">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="bank_name" name="bank_name" class="form-control"
                                           placeholder="Enter Bank Name"
                                           value="{{old('bank_name',optional($kyc)->bank_name)}}" required>
                                    <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                </div>
                                @foreach($errors->get('bank_name') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-md-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="bank_branch" name="bank_branch" class="form-control"
                                           placeholder="Enter Bank Branch"
                                           value="{{old('bank_branch',optional($kyc)->bank_branch)}}" required>
                                    <label for="bank_branch">Bank Branch <span class="text-danger">*</span></label>
                                </div>
                                @foreach($errors->get('bank_branch') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title-wrap">
                            <h5 class="card-title mb-0">Upload Documents</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-3 col-12 filter-item">
                                <label for="example-input-large" class="required">
                                    PAN Card Image
                                </label>
                                <input type="file" class="filePondInput" name="pan_card_image"
                                       value="{{ $panCardImage }}"
                                       accept="image/*" required>
                                @foreach($errors->get('pan_card_image') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-3 col-12 filter-item">
                                <label for="example-input-large" class="required">
                                    Aadhaar Card Front Image
                                </label>
                                <input type="file" class="filePondInput" name="aadhaar_card_image"
                                       value="{{ $aadhaarCardImage }}" accept="image/*" required>
                                @foreach($errors->get('aadhaar_card_image') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-3 col-12 filter-item">
                                <label for="example-input-large" class="required">
                                    Aadhaar Card Back Image
                                </label>
                                <input type="file" class="filePondInput" name="aadhaar_card_back_image"
                                       value="{{ $aadhaarCardBackImage }}" accept="image/*" required>
                                @foreach($errors->get('aadhaar_card_back_image') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-3 col-12 filter-item">
                                <label for="example-input-large" class="required">
                                    Cancel Cheque Or Bank PassBook Front Page Image
                                </label>
                                <input type="file" class="filePondInput" name="cancel_cheque_image"
                                       value="{{ $cancelChequeImage }}" accept="image/*" required>
                                @foreach($errors->get('cancel_cheque_image') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            @if(!$kyc || !$kyc->isApproved())
                                <div class="col-12">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="uil uil-message me-1"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('page-javascript')
    <script src="{{ asset('js/vapor.min.js') }}"></script>
    <script src="{{ asset('js/filepond.min.js') }}"></script>
    <script>
        var bankIfsc = $('#bank_ifsc');
        var bankIfscError = $('#bank_ifsc_error');
        var bankName = $('#bank_name');
        var bankBranch = $('#bank_branch');

        bankIfsc.on('input', function () {
            var ifscCode = bankIfsc.val();

            if (ifscCode.length === 11) {
                $.ajax({
                    'url': 'https://ifsc.razorpay.com/' + ifscCode,
                    'success': function (res, status, xhr) {
                        if (xhr.status === 200) {
                            bankName.val(res.BANK);
                            bankBranch.val(res.BRANCH);
                            bankIfscError.html('');
                        }
                    }, 'error': function (xhr, status, error) {
                        if (xhr.status === 404) {
                            bankIfscError.siblings('.backendError').remove();
                            bankIfscError.html('Please enter a valid IFSC Code');
                        }
                    }
                })
            } else {
                bankName.val('');
                bankBranch.val('');
                bankIfscError.html('');
            }
        });

    </script>
@endpush
