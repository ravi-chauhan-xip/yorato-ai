@if($model->isBlockchainStatusSuccess())
    <span class="btn btn-success btn-sm">Success</span>
@elseif($model->isBlockchainStatusPending())
    <span class="btn btn-primary btn-sm">Pending</span>
@elseif($model->isBlockchainStatusProcessing())
    <span class="btn btn-info btn-sm">Processing</span>
@elseif($model->isBlockchainStatusVerifying())
    <span class="btn btn-warning btn-sm">Verifying</span>
@else
    <span class="btn btn-danger btn-sm">Failed</span>
@endif
