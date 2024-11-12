@if($address)
    <button class="btn btn-link p-0" type="button" data-clipboard-text="{{ $address }}"
            data-title="Click To Copy" data-toggle="tooltip" data-placement="bottom"
    >
        {{ substr($address, 0, 5). "..." .substr($address, -5) }}
    </button>
@else
    N/A
@endif
