@if($model->status == \App\Models\Member::STATUS_FREE_MEMBER)
    <div class="btn btn btn-danger btn-sm">
        Free
    </div>
@endif
@if($model->status == \App\Models\Member::STATUS_ACTIVE)
    <div class="btn btn btn-success btn-sm">
        Active
    </div>
@endif
@if($model->status == \App\Models\Member::STATUS_BLOCKED)
    <div class="btn btn-dark btn-sm">
        Blocked
    </div>
@endif
