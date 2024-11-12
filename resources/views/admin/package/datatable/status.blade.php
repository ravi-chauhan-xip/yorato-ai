@if($model->isActive())
    <span class="btn btn-success btn-sm waves-effect waves-light">
        {{ $model->present()->status() }}
    </span>
@endif
@if($model->isInActive())
    <span class="btn btn-danger btn-sm waves-effect waves-light">
        {{ $model->present()->status() }}
    </span>
@endif
