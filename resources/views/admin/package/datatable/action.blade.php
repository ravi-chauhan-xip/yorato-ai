@if($model->isActive())
    <a href="{{ route('admin.packages.change-status', ['package' => $model, 'status' => \App\Models\Package::STATUS_INACTIVE]) }}"
       class="btn btn-danger btn-sm">
        <i class='bx bx-window-close me-1'></i> In-Activate
    </a>
@elseif($model->isInActive())
    <a href="{{ route('admin.packages.change-status', ['package' => $model, 'status' => \App\Models\Package::STATUS_ACTIVE]) }}"
       class="btn btn-success btn-sm">
        <i class='bx bx-check me-1'></i> Activate
    </a>
@endif
