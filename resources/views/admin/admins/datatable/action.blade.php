@can('Admins-update')
    <a href="{{ route('admin.admins.edit', $model) }}" class="btn btn-primary btn-sm">
        <i class="uil uil-edit-alt me-1"></i> Edit
    </a>
    <a href="{{ route('admin.admins.change-password', $model) }}" class="btn btn-dark btn-sm">
        <i class="uil uil-lock me-1"></i> Change Password
    </a>
    @if ($model->isInActive())
        <a href="{{ route('admin.admins.update-status',$model) }}" class="btn btn-success btn-sm">
            <i class="uil uil-check me-1"></i>
            Active
        </a>
    @endif
    @if ($model->isActive())
        <a href="{{ route('admin.admins.update-status',$model) }}" class="btn btn-danger btn-sm">
            <i class="uil uil-ban me-1"></i>
            Inactive
        </a>
    @endif
@endcan
