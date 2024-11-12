@if($model->status == \App\Models\CompanyWallet::STATUS_ACTIVE)
    <span
        class="btn btn-success btn-sm"> Active </span>
@endif
@if($model->status ==  \App\Models\CompanyWallet::STATUS_IN_ACTIVE)
    <span
        class="btn btn-danger btn-sm"> In-Active </span>
@endif
