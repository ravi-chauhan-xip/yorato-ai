@if($model->status == \App\Models\StakeCoin::STATUS_PENDING)
    <div class="btn btn-info btn-sm">
        Pending
    </div>
@endif
@if($model->isActive())
    <div class="btn btn-success btn-sm">
        Active
    </div>
@endif
@if($model->isInActive())
    <div class="btn btn-danger btn-sm">
        Finish
    </div>
@endif
