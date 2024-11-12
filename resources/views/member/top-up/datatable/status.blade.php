@if($model->status == \App\Models\TopUp::STATUS_SUCCESS)
    <div class="btn btn-success btn-sm">
        Success
    </div>
@endif
@if($model->status == \App\Models\TopUp::STATUS_PENDING)
    <div class="btn btn-danger btn-sm">
        Pending
    </div>
@endif
