@if($model->isOpen())
    <span class="btn btn-sm btn-success">
        {{ $model->present()->status() }}
    </span>
@endif
@if($model->isClose())
    <span class="btn btn-sm btn-danger">
        {{ $model->present()->status() }}
    </span>
@endif
