@if($model->status == \App\Models\Member::STATUS_FREE_MEMBER )
    <div class="btn btn-danger btn-sm">
        {{ $model->present()->status() }}
    </div>
@endif
@if($model->status == \App\Models\Member::STATUS_ACTIVE )
    <div class="btn btn-success btn-sm">
        {{ $model->present()->status() }}
    </div>
@endif
@if($model->status == \App\Models\Member::STATUS_BLOCKED)
    <div class="btn btn-dark btn-sm">
        {{ $model->present()->status() }}
    </div>
@endif
