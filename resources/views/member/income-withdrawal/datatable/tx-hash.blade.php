@if ($model->tx_hash)
    <a href="{{ env('BSC_SCAN_URL') }}/tx/{{ $model->tx_hash }}" class="btn btn-label-warning btn-sm text-none"
       target="_blank">
        <i class="uil uil-external-link-alt"></i>
        @if (strlen($model->tx_hash) >= 20)
            {{ substr($model->tx_hash, 0, 5). "..." .substr($model->tx_hash, -5) }}
        @else
            {{ $model->tx_hash }}
        @endif
    </a>
@else
    N/A
@endif
