@extends('admin.layouts.master')

@section('title', 'Settings')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('admin.settings.update') }}" class="row">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            @if(app()->isProduction())
                                <div class="form-group col-12 mb-3">
                                    <label class="required">System Password</label>
                                    <input type="password" class="form-control" name="system_password"
                                           value="{{ old('system_password', settings('system_password')) }}"
                                           {{ app()->isProduction() ? 'required': '' }} placeholder="Enter System Password"
                                    >
                                    @foreach($errors->get('system_password') as $error)
                                        <span class="text-danger">{{ $error }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">SMS Enabled</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="sms_enabled" id="sms_enabled_1"
                                           value="1"
                                           {{ request('sms_enabled') == '1' || settings('sms_enabled') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="sms_enabled_1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sms_enabled" id="sms_enabled_0"
                                           value="0"
                                           {{ request('sms_enabled') == '0' || !settings('sms_enabled') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="sms_enabled_0">No</label>
                                </div>
                                @foreach($errors->get('sms_enabled') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">Ecommerce Look</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="is_ecommerce"
                                           id="is_ecommerce_1"
                                           value="1"
                                           {{ request('is_ecommerce') == '1' || settings('is_ecommerce') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="is_ecommerce_1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_ecommerce"
                                           id="is_ecommerce_0"
                                           value="0"
                                           {{ request('is_ecommerce') == '0' || !settings('is_ecommerce') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="is_ecommerce_0">No</label>
                                </div>
                                @foreach($errors->get('is_ecommerce') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">Social Links</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="social_link"
                                           id="social_link_1"
                                           value="1"
                                           {{ request('social_link') == '1' || settings('social_link') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="social_link_1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="social_link"
                                           id="social_link_0"
                                           value="0"
                                           {{ request('social_link') == '0' || !settings('social_link') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="social_link_0">No</label>
                                </div>
                                @foreach($errors->get('social_link') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">Front Template</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="front_template"
                                           id="front_template_classic"
                                           value="classic"
                                           {{ request('front_template', settings('front_template')) == 'classic' ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="front_template_classic">Classic</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="front_template"
                                           id="front_template_modern"
                                           value="modern"
                                           {{ request('front_template', settings('front_template')) == 'modern' ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="front_template_modern">Modern</label>
                                </div>
                                @foreach($errors->get('front_template') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">Address Display</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="address_enabled"
                                           id="address_enabled_1"
                                           value="1"
                                           {{ request('address_enabled') == '1' || settings('address_enabled') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="address_enabled_1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="address_enabled"
                                           id="address_enabled_0"
                                           value="0"
                                           {{ request('address_enabled') == '0' || !settings('address_enabled') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="address_enabled_0">No</label>
                                </div>
                                @foreach($errors->get('address_enabled') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <small class="text-light fw-semibold d-block required">Payment Gateway</small>
                                <div class="form-check form-check-inline mt-3">
                                    <input class="form-check-input" type="radio" name="payment_gateway"
                                           id="payment_gateway_1"
                                           value="1"
                                           {{ request('payment_gateway') == '1' || settings('payment_gateway') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="payment_gateway_1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_gateway"
                                           id="payment_gateway_0"
                                           value="0"
                                           {{ request('payment_gateway') == '0' || !settings('payment_gateway') ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="payment_gateway_0">No</label>
                                </div>
                                @foreach($errors->get('payment_gateway') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="tds_percent" step="0.01" min="0.01"
                                           id="tds_percent" value="{{ old('tds_percent', settings('tds_percent')) }}"
                                           required>
                                    <label for="tds_percent" class="required">TDS Percentage (%)</label>
                                </div>
                                @foreach($errors->get('tds_percent') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="number" class="form-control" name="admin_charge_percent" step="0.01"
                                           min="0" id="admin_charge_percent"
                                           value="{{ old('admin_charge_percent', settings('admin_charge_percent')) }}"
                                           required>
                                    <label for="admin_charge_percent" class="required">Admin Charge Percentage
                                        (%)</label>
                                </div>
                                @foreach($errors->get('admin_charge_percent') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-6 mb-3">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input class="form-control" type="color" id="primary_color" name="primary_color"
                                           value="{{ old('primary_color', settings('primary_color')) }}"
                                           autocomplete="off" required>
                                    <label for="primary_color" class="required">Primary Color</label>
                                </div>
                                @foreach($errors->get('primary_color') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-6 mb-3">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input class="form-control" type="color" id="primary_color_hover"
                                           name="primary_color_hover"
                                           value="{{ old('primary_color_hover', settings('primary_color_hover')) }}"
                                           autocomplete="off" required>
                                    <label for="primary_color_hover" class="required">Primary Hover Color</label>
                                </div>
                                @foreach($errors->get('primary_color_hover') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>

                            <div class="form-group col-lg-12 mb-3">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-3">
                                        <i class="uil uil-message me-1"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
