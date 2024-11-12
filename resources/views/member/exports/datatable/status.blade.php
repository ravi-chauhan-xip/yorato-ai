@if($model->isPending())
    <span class="btn btn-warning btn-sm waves-effect waves-light">
        Pending
    </span>
@endif
@if($model->isCompleted())
    <span class="btn btn-success btn-sm waves-effect waves-light">
        Completed
    </span>
@endif
@if($model->isFailed())
    <span class="btn btn-danger btn-sm waves-effect waves-light">
        Failed
    </span>
@endif
