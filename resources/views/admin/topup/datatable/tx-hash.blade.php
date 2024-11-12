@if ($model->userDeposit && $model->userDeposit->transaction_hash)
    <a href="{{ env('BSC_SCAN_URL') }}/tx/{{ $model->userDeposit->transaction_hash }}" class="btn btn-label-dark btn-sm text-none"
       target="_blank">
        <i class="uil uil-external-link-alt"></i>
        @if (strlen($model->userDeposit->transaction_hash) >= 20)
            {{ substr($model->userDeposit->transaction_hash, 0, 5). "..." .substr($model->userDeposit->transaction_hash, -5) }}
        @else
            {{ $model->userDeposit->transaction_hash }}
        @endif
    </a>
@else
    N/A
@endif
