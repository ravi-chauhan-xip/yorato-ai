@if($model->isProcessing() && !$model->tx_hash && $model->isBlockchainStatusFailed())
    <a href="{{ route('admin.income-withdrawal-requests.retry-transfer', $model) }}"
       class="btn btn-warning btn-sm mb-1">
        <i class="uil uil-sync"></i>
        Retry {{env('APP_CURRENCY')}} Transfer
    </a>
@else
    N/A
@endif


