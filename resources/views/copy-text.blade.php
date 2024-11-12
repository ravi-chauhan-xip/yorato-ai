@if(empty($text))
    N/A
@else
    <button class="btn btn-link p-0 shadow-none" type="button" data-clipboard-text="{{ $text }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Click To Copy">{{ $text }}</button>
@endif
