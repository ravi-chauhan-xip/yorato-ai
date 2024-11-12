@extends('admin.layouts.master')
@section('title') Update Package @endsection

@section('content')
    @include('admin.breadcrumbs', [
         'crumbs' => [
             'Update Package'
         ]
    ])
    <form method="post">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">PACKAGE INFORMATION</h4>
                        <p class="sub-header"> Adding your package Details</p>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group mb-3">
                                    <label>Name</label>
                                    <input type="text" required name="name" class="form-control"
                                           placeholder="Enter Name" value="{{$package->name }}">
                                    @foreach($errors->get('name') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter package name</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-3">
                                    <label>Base Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                        </div>
                                        <input type="number" required name="base_amount"
                                               value="{{$package->base_amount }}" class="form-control"
                                               placeholder="Enter Base Amount">

                                    </div>
                                    @foreach($errors->get('base_amount') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter Base Amount</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group mb-3">
                                    <label>Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                        </div>
                                        <input type="number" required name="amount" class="form-control"
                                               placeholder="Enter Amount" value="{{ $package->amount }}">

                                    </div>
                                    @foreach($errors->get('amount') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter amount</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label>Capping</label>
                                    <input type="number" required name="capping" class="form-control"
                                           placeholder="Enter Capping" value="{{$package->capping }}">
                                    @foreach($errors->get('capping') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter capping amount</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label>PV</label>
                                    <input type="number" required name="pv" class="form-control" placeholder="Enter PV"
                                           value="{{$package->pv }}">
                                    @foreach($errors->get('pv') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter pv amount</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                            {{--<div class="col-3">
                                <div class="form-group mb-3">
                                    <label>Weekly ROI</label>
                                    <input type="text" name="roi" class="form-control" placeholder="Enter Weekly ROI" value="{{$package->}}">
                                    @foreach($errors->get('roi') as $error)
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">Please enter roi</li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>--}}
                            <div class="col-3">
                                <div class="form-group mb-3">
                                    <label>Status</label>
                                    <select class="form-control" name="status" data-toggle="select2" required>
                                        <option>Select Status</option>
                                        <option value="1" {{ $package->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="2" {{ $package->status == 2 ? 'selected' : '' }}>InActive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <div id="summernote-editor">"Certified that I am at least 18 years of age. I have
                                        read completely and understood all the Terms and Conditions for availing
                                        PACKAGE. I have received complete “DJ Enterprize” Online immediately after
                                        registration. I am completely satisfied with DJ Enterprize Product/services.I
                                        have completely understood how to learn courses given in Package online by
                                        myself (using Computer/mobile and Internet at my own cost). I am aware that I
                                        can evaluate the purchased Product online and the cost of the product would only
                                        be sent, if completely satisfied. I am aware that the product cost could be sent
                                        only if satisfied to DJ Enterprize office within 15 days after the date of
                                        registration for Purchase. The refund policy is understood by me very clearly.I
                                        am aware of all the DJ Enterprize . TERMINATION AND CANCELLATION POLICIES and
                                        adhere to the consequences if any. I have carefully read the Terms and
                                        Conditions and FAQs applicable to DJ Enterprize. as given on website www.DJ
                                        Enterprize .com and agree / accept to them. I am signing this DECLARATION with
                                        complete understanding and with my own WILL, without any PRESSURE and INFLUENCE.
                                        I am aware that any dispute arising out of this purchase would first be solved
                                        as per Terms and Conditions of the company, failing which could be addressed
                                        exclusively in jurisdiction of (INDIA) only."
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="text-sm-center">
                                    <button class="btn btn-primary"><i class="uil uil-message me-1"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('import-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css"/>
@endsection

@push('page-javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
    <script>
        jQuery(document).ready(function(){$("#summernote-editor").summernote({height:250,minHeight:null,maxHeight:null,focus:!1}),$("#summernote-inline").summernote({airMode:!0})});
    </script>
@endpush
