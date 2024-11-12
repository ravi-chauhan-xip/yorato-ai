@if($model->done_by == \App\Models\TopUp::DONE_BY_ADMIN)
    <div class="btn btn-success btn-sm">
        Admin
    </div>
@endif
@if($model->done_by == \App\Models\TopUp::DONE_BY_MEMBER)
    <div class="btn btn-info btn-sm">
        Wallet
    </div>
@endif
@if($model->done_by == \App\Models\TopUp::DONE_BY_WEB3)
    <div class="btn btn-primary btn-sm">
        Web3
    </div>
@endif
