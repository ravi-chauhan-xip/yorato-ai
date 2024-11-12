@if($model->isNewFree())
    <div class="btn btn-danger btn-sm">
        {{ $model->present()->newStatus() }}
    </div>
@endif
@if($model->isNewActive())
    <div class="btn btn-success btn-sm">
        {{ $model->present()->newStatus() }}
    </div>
@endif
@if($model->isNewBlocked())
    <div class="btn btn-dark btn-sm">
        {{ $model->present()->newStatus() }}
    </div>
@endif
