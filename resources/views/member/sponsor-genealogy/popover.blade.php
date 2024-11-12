<div class='row'>
    <div class='col-6 mb-3'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-1'>Joining Date :</h6>
        <p class='mb-1'> {{  $member->created_at->dateFormat()  }}</p>
    </div>
    <div class='col-6 mb-3'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-1'>Activation Date :</h6>
        <p class='mb-1'>{{ optional($member->activated_at)->dateFormat()??'N/A' }}</p>
    </div>

    <div class='col-6 mb-3'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-1'>Sponsor Wallet Address :</h6>
        <p class='mb-1'>{{ $member->sponsor ? walletAddress($member->sponsor->user->wallet_address) : 'N/A'}}</p>
    </div>
    <div class='col-6 mb-3'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-1'>Direct :</h6>
        <p class='mb-0'> {{ $member->sponsored_count }} </p>
    </div>
    <div class='col-6 mb-3'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-1'>Package :</h6>
        <p class='mb-3'>
            @if($member->package)
                {{ $member->package->name }}
                ({{ env('APP_CURRENCY') }} {{ toHumanReadable($member->package->amount) }} )
            @else
                N/A
            @endif
        </p>
    </div>
</div>
