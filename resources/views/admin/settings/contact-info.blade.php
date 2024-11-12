@extends('admin.layouts.master')
@section('title','Contact Info')

@section('content')
    @include('admin.breadcrumbs', [
        'crumbs' => [
            'Contact Info'
        ]
   ])
    <form method="post">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name"
                                           value="{{ old('company_name',settings('company_name')) }}" required>
                                    <label for="company_name" class="required">Company Name</label>
                                </div>
                                @foreach($errors->get('company_name') as $error)
                                    <div class="text-danger font-weight-bold">{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="address_line_1" id="address_line_1"
                                           value="{{ old('address_line_1',settings('address_line_1')) }}" placeholder="Enter Address Line 1">
                                    <label for="address_line_1">Address Line 1</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="address_line_2" id="address_line_2"
                                           value="{{ old('address_line_2',settings('address_line_2')) }}" placeholder="Enter Address Line 2">
                                    <label for="address_line_2">Address Line 2</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select name="country_id" class="form-control State" id="country"
                                            data-toggle="select2">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id || settings('country') == $country->name ? 'selected' : '' }}
                                            >
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="country">Country</label>
                                </div>
                                @foreach($errors->get('country_id') as $error)
                                    <div class="text-danger font-weight-bold">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select name="state_id" class="form-control"
                                            id="state_id" data-toggle="select2">
                                        <option value="">Select State</option>
                                    </select>
                                    <label for="state_id">State</label>
                                </div>
                                @foreach($errors->get('state_id') as $error)
                                    <div class="text-danger font-weight-bold">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <select name="city_id" class="form-control"
                                            id="city_id" data-toggle="select2">
                                        <option value="">Select City</option>
                                    </select>
                                    <label for="city_id">City</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="pincode" id="pincode"
                                           value="{{ old('pincode',settings('pincode')) }}"
                                           autocomplete="off"
                                           onkeypress="return isNumberKey(event)" placeholder="Enter Pincode">
                                    <label for="pincode">Pincode</label>
                                </div>
                                @foreach($errors->get('pincode') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="mobile" name="mobile"
                                           value="{{ old('mobile',settings('mobile')) }}" placeholder="Enter Mobile Number"
                                           autocomplete="off"
                                           onkeypress="return isNumberKey(event)" onfocusout="mobile_verify()">
                                    <label for="mobile">Mobile Number</label>
                                </div>
                                @foreach($errors->get('mobile') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="email" id="email" class="form-control" name="email"
                                           value="{{ old('email',settings('email')) }}" placeholder="Enter Email ID">
                                    <label for="email">Email ID</label>
                                </div>
                                @foreach($errors->get('email') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="facebook_url" id="facebook_url"
                                           value="{{  old('facebook_url',settings('facebook_url')) }}" placeholder="Enter Facebook Link">
                                    <label for="facebook_url">Facebook Link</label>
                                </div>
                                @foreach($errors->get('facebook_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="instagram_url" id="instagram_url"
                                           value="{{ old('instagram_url',settings('instagram_url')) }}" placeholder="Enter Instagram Link">
                                    <label for="instagram_url">Instagram Link</label>
                                </div>
                                @foreach($errors->get('instagram_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="youtube_url" id="youtube_url"
                                           value="{{ old('youtube_url',settings('youtube_url')) }}" placeholder="Enter Youtube Link">
                                    <label for="youtube_url">Youtube Link</label>
                                </div>
                                @foreach($errors->get('youtube_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="twitter_url" id="twitter_url"
                                           value="{{ old('twitter_url',settings('twitter_url')) }}" placeholder="Enter Twitter Link">
                                    <label for="twitter_url">Twitter Link</label>
                                </div>
                                @foreach($errors->get('twitter_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="telegram_url" id="telegram_url"
                                           value="{{ old('telegram_url',settings('telegram_url')) }}" placeholder="Enter Telegram Link">
                                    <label for="telegram_url">Telegram Link</label>
                                </div>
                                @foreach($errors->get('telegram_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="telegram_group_url"
                                           id="telegram_group_url"
                                           value="{{ old('telegram_group_url',settings('telegram_group_url')) }}" placeholder="Enter Telegram Group Link">
                                    <label for="telegram_group_url">Telegram Group Link</label>
                                </div>
                                @foreach($errors->get('telegram_group_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-lg-4 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" name="zoom_url" id="zoom_url"
                                           value="{{ old('zoom_url',settings('zoom_url')) }}" placeholder="Enter Zoom Link">
                                    <label for="zoom_url">Zoom Link</label>
                                </div>
                                @foreach($errors->get('zoom_url') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="uil uil-message me-1"></i> Submit
                </button>
            </div>
        </div>
    </form>
@endsection

@push('page-javascript')
    <script>
        // 1. Number Key
        function isNumberKey(evt) {
            let charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode != 46 && charCode != 45 && charCode > 31
                && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        // 2. Max Length & Prevent Enter Button to Refresh the Page//
        function max_length(obj, e, max) {
            e = e || event;
            max = max;
            if (e.keyCode === 13) {
                event.preventDefault();
            }
            if (obj.value.length >= max && e.keyCode > 46) {
                return false;
            }
            return true;
        }

        $(document).ready(function () {
            @if (old('state_id', settings('state')))
            getCity('{{ old('state_id', settings('state')) }}');
            @endif
            @if (old('country_id', settings('country')))
            getState('{{ old('country_id', settings('country')) }}');
            @endif
            $('#country').on('change', function () {
                if ($(this).val()) {
                    $.ajax({
                        url: '/admin/get/state/' + $(this).val() + '',
                        success: function (data) {
                            var select = ' <option value="">Select State</option>';
                            $.each(data, function (key, value) {
                                select += '<option value=' + value.id + '>' + value.name + '</option>';
                            });
                            $('#state_id').html(select);

                        }
                    });
                } else {
                    $('#state_id').html('<option value="">Select State</option>');
                }

                $('#city_id').html('<option value="">Select City</option>');
            })
            $('#state_id').on('change', function () {
                if ($(this).val()) {
                    $.ajax({
                        url: '/admin/get/city/' + $(this).val() + '',
                        success: function (data) {
                            var select = ' <option value="">Select City</option>';
                            $.each(data, function (key, value) {
                                select += '<option value=' + value.id + '>' + value.name + '</option>';
                            });
                            $('#city_id').html(select);

                        }
                    });
                } else {
                    $('#city_id').html('<option value="">Select City</option>');
                }
            })

        });

        function getState(idOrName) {
            if (idOrName) {
                $.ajax({
                    url: '/admin/get/state/' + idOrName + '',
                    success: function (data) {
                        var select = ' <option value="">Select State</option>';
                        $.each(data, function (key, value) {
                            select += '<option value="' + value.id + '" ' + (value.id == '{{ old('state_id') }}' || value.name == '{{ settings('state') }}' ? 'selected' : '') + '>' + value.name + '</option>';
                        });
                        $('#state_id').html(select);
                    }
                });

            } else {
                $('#state_id').html('<option value="">Select State</option>');
            }
        }

        function getCity(idOrName) {
            if (idOrName) {
                $.ajax({
                    url: '/admin/get/city/' + idOrName + '',
                    success: function (data) {
                        var select = ' <option value="">Select City</option>';
                        $.each(data, function (key, value) {
                            select += '<option value="' + value.id + '" ' + (value.id == '{{ old('city_id') }}' || value.name == '{{ settings('city') }}' ? 'selected' : '') + '>' + value.name + '</option>';
                        });
                        $('#city_id').html(select);
                    }
                });

            } else {
                $('#city_id').html('<option value="">Select City</option>');
            }
        }
    </script>
@endpush
