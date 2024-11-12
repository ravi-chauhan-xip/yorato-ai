@extends('admin.layouts.master')
@section('title',' Create Admin')
@section('content')
    @include('admin.breadcrumbs', [
       'crumbs' => [
           'Create Admin'
       ]
   ])
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <div class="form-group col-xl">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="name"
                                           value="{{ old('name') }}" name="name" placeholder="Enter Name" required>
                                    <label for="name" class="required">Name</label>
                                </div>
                                @foreach($errors->get('name') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-xl">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" placeholder="Enter Email"
                                           value="{{ old('email') }}" id="email"
                                           name="email" required>
                                    <label for="email" class="required">Email</label>
                                </div>
                                @foreach($errors->get('email') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-xl">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="number" placeholder="Enter Mobile"
                                           value="{{ old('mobile') }}" id="mobile"
                                           name="mobile" required>
                                    <label for="mobile" class="required">Mobile</label>
                                </div>
                                @foreach($errors->get('mobile') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-xl">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control" id="password" name="password"
                                                   required placeholder="Enter Password">
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </span>
                                    </div>
                                </div>
                                @foreach($errors->get('password') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group col-xl">
                                <label for="name" class="form-label required">Is Super Admin </label> <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_super" id="yes"
                                           onclick="checkPermissionTable(this.value)"
                                           value="1" {{ old('is_super')?'checked':'' }} >
                                    <label class="form-check-label" for="yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_super" id="no"
                                           onclick="checkPermissionTable(this.value)"
                                           value="0" {{ !old('is_super')?'checked':'' }}>
                                    <label class="form-check-label" for="no">No</label>
                                </div>
                                @foreach($errors->get('is_super') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row" id="permissionTable">
                            <div class="col-12">
                                @foreach($errors->get('permissions') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Permission</th>
                                            <th>All</th>
                                            <th>Create</th>
                                            <th>Read</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($permissions as $name => $permission)
                                            <tr>
                                                <td>{{ ucfirst($name) }}</td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input checkbox-row" type="checkbox" id="c{{ $name }}">
                                                        <label class="form-check-label" for="c{{ $name }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[0]->id }}" value="{{ $permission[0]->name }}"
                                                               name="permissions[]"/>
                                                        <label class="form-check-label" for="c{{ $permission[0]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[1]->id }}" value="{{ $permission[1]->name }}"
                                                               name="permissions[]"/>
                                                        <label class="form-check-label" for="c{{ $permission[1]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[2]->id }}" value="{{ $permission[2]->name }}"
                                                               name="permissions[]"/>
                                                        <label class="form-check-label" for="c{{ $permission[2]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[3]->id }}" value="{{ $permission[3]->name }}"
                                                               name="permissions[]"/>
                                                        <label class="form-check-label" for="c{{ $permission[3]->id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 text-center">
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-danger me-2">
                                <i class="uil uil-multiply me-1"></i>
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="uil uil-message me-1"></i>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-javascript')
    <script>
        $(document).ready(function () {
            @if(old('is_super'))
            checkPermissionTable(1);
            @endif
        });
        $('.checkbox-row').on('change', function () {
            toggleRow($(this));
        });

        $('.checkbox-item').on('change', function () {
            let checked = true;

            $(this).parents('tr').first()
                .children('td')
                .children('div')
                .children('.checkbox-item')
                .each(function (index, el) {
                    if (!$(el).prop('checked')) {
                        checked = false;
                    }
                });

            $(this).parents('tr').first()
                .children('td')
                .children('div')
                .children('.checkbox-row')
                .prop('checked', checked);
        });

        function toggleRow(el) {
            if (el.prop('checked')) {
                el.parents('td').siblings('td').children('div').children('input').prop('checked', true);
            } else {
                el.parents('td').siblings('td').children('div').children('input').prop('checked', false);
            }
        }

        function toggleRow(el) {
            if (el.prop('checked')) {
                el.parents('td').siblings('td').children('div').children('input').prop('checked', true);
            } else {
                el.parents('td').siblings('td').children('div').children('input').prop('checked', false);
            }
        }

        function checkPermissionTable(val) {
            $('#permissionTable').show();
            if (val == 1) {
                $('#permissionTable').hide();
            }

        }

        $("#basic-datepicker").flatpickr({
            maxDate: new Date(),
            defaultDate: ["{{ old('dob') }}"],
            dateFormat: 'd-m-Y'
        });
    </script>
@endpush
