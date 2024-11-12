@if($model->isLastFree())
    <div class="btn btn-danger btn-sm">
        {{ $model->present()->lastStatus() }}
    </div>
@endif
@if($model->isLastActive())
    <div class="btn btn-success btn-sm">
        {{ $model->present()->lastStatus() }}
    </div>
@endif
@if($model->isLastBlocked())
    <div class="btn btn-dark btn-sm">
        {{ $model->present()->lastStatus() }}
    </div>
@endif
