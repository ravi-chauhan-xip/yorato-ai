@extends('admin.layouts.master')

@section('title')
    Edit Admin
@endsection

@section('content')
    @include('admin.breadcrumbs', [
     'crumbs' => [
         'Edit Admin'
     ]
])
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <h5 class="card-header">Edit Admin</h5>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.admins.update',$admin) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 col-xl">
                                <label class="required" for="name">Name</label>
                                <input id="name" type="text" required name="name" class="form-control"
                                       placeholder="Enter Name"
                                       value="{{ old('name',$admin->name) }}">
                                @foreach($errors->get('name') as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            </div>
                            <div class="form-group mb-3 col-xl">
                                <label for="email" class="required">Email</label>
                                <input id="email" type="email" name="email"
                                       value="{{ old('email',$admin->email) }}"
                                       class="form-control" required autocomplete="off"
                                       placeholder="Enter Email">
                                @foreach($errors->get('email') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group mb-3 col-xl">
                                <label for="mobile" class="required">
                                    Mobile
                                </label>
                                <input id="mobile" type="text" name="mobile"
                                       value="{{ old('mobile',$admin->mobile) }}"
                                       required
                                       class="form-control" placeholder="Enter Mobile">
                                @foreach($errors->get('mobile') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                            <div class="form-group col-xl">
                                <label for="name" class="form-label required">Is Super Admin </label> <br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           onclick="checkPermissionTable(this.value)" name="is_super" id="yes"
                                           value="1" {{ old('is_super',$admin->is_super)?'checked':'' }} >
                                    <label class="form-check-label" for="yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           onclick="checkPermissionTable(this.value)" name="is_super" id="no"
                                           value="0" {{ !old('is_super',$admin->is_super)?'checked':'' }}>
                                    <label class="form-check-label" for="no">No</label>
                                </div>
                                @foreach($errors->get('is_super') as $error)
                                    <span class="text-danger">{{ $error }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" id="permissionTable">
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
                                                <td>{{ $name }}</td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input checkbox-row" type="checkbox"
                                                               id="c{{ $name }}" {{ $admin->permissions->whereIn('id', $permission->pluck('id'))->count() === 4  ? 'checked="checked"' : '' }}>
                                                        <label class="form-check-label" for="c{{ $name }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[0]->id }}"
                                                               value="{{ $permission[0]->name }}"
                                                               name="permissions[]" {{ $admin->permissions->where('id', $permission[0]->id)->count() ? 'checked="checked"' : '' }}
                                                        >
                                                        <label class="form-check-label"
                                                               for="c{{ $permission[0]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[1]->id }}"
                                                               value="{{ $permission[1]->name }}"
                                                               name="permissions[]" {{ $admin->permissions->where('id', $permission[1]->id)->count() ? 'checked="checked"' : '' }}
                                                        >
                                                        <label class="form-check-label"
                                                               for="c{{ $permission[1]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[2]->id }}"
                                                               value="{{ $permission[2]->name }}"
                                                               name="permissions[]" {{ $admin->permissions->where('id', $permission[2]->id)->count() ? 'checked="checked"' : '' }}
                                                        >
                                                        <label class="form-check-label"
                                                               for="c{{ $permission[2]->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check mb-1">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="c{{ $permission[3]->id }}"
                                                               value="{{ $permission[3]->name }}"
                                                               name="permissions[]"
                                                            {{ $admin->permissions->where('id', $permission[3]->id)->count() ? 'checked="checked"' : '' }}
                                                        >
                                                        <label class="form-check-label"
                                                               for="c{{ $permission[3]->id }}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group mt-4 text-center">
                                <a href="{{ route('admin.admins.index') }}" class="btn btn-danger me-2">
                                    <i class="uil uil-sign-out-alt"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="uil uil-message"></i>
                                    Submit
                                </button>
                            </div>
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
            @if(old('is_super',$admin->is_super))
            checkPermissionTable({{ old('is_super',$admin->is_super) }});
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

        function checkPermissionTable(val) {
            $('#permissionTable').show();
            if (val == 1) {
                $('#permissionTable').hide();
            }

        }

    </script>
@endpush
