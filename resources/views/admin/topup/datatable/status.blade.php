@if($model->status == \App\Models\TopUp::STATUS_SUCCESS)
    <div class="btn btn-success btn-sm">
        Success
    </div>
@endif
@if($model->status == \App\Models\TopUp::STATUS_PENDING)
    <div class="btn btn-info btn-sm">
        Pending
    </div>
@endif
@if($model->status == \App\Models\TopUp::STATUS_FAIL)
    <div class="btn btn-info btn-sm">
        Fail
    </div>
@endif
