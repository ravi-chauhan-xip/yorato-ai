@if($model->kyc->isNotApplied())
    <a href="javascript:voide()" class="btn btn-primary btn-sm" >
        Not Applied
    </a>
@elseif($model->kyc->isPending())
    <a href="{{ route('admin.kycs.show', $model->kyc) }}" class="btn btn-warning btn-sm" target="_blank">
        {{ $model->present()->kycStatus() }}
    </a>
@elseif($model->kyc->isApproved())
    <a href="{{ route('admin.kycs.show', $model->kyc) }}" class="btn btn-success btn-sm" target="_blank">
        {{ $model->present()->kycStatus() }}
    </a>
@elseif($model->kyc->isRejected())
    <a href="{{ route('admin.kycs.show', $model->kyc) }}" class="btn btn-danger btn-sm" target="_blank">
        {{ $model->present()->kycStatus() }}
    </a>
@endif
