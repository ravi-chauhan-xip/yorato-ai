<div class='row'>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Joining Date :
            <span class='mb-1 text-dark'> {{  $member->created_at->dateFormat()  }}</span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Activation Date :
            <span class='mb-1 text-dark'>{{ $member->activated_at ? $member->activated_at->dateFormat() : 'N/A' }}</span>
        </h6>
    </div>

    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Package :
            <span class='mb-1 text-dark'>
                @if($member->package)
                    {{ $member->package->name }}
                    ({{ env('APP_CURRENCY') }}{{ toHumanReadable($member->package->amount) }} )
                @else
                    N/A
                @endif
        </span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Users(L/R) :
            <span class='mb-1 text-dark'> {{ $member->left_count }} / {{$member->right_count}}</span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Total Top Up(L/R) :
            <span class='mb-1 text-dark'> {{ toHumanReadable($member->left_bv) }} / {{ toHumanReadable($member->right_bv) }}</span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Remaining Top Up(L/R) :
            <span class='mb-1 text-dark'> {{ toHumanReadable($member->left_power) }} / {{ toHumanReadable($member->right_power) }}</span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Total Staking(L/R) :
            <span class='mb-1 text-dark'> {{ toHumanReadable($member->left_stake_bv) }} / {{ toHumanReadable($member->right_stake_bv) }}</span>
        </h6>
    </div>
    <div class='col-12'>
        <h6 class='text-primary font-weight-600 text-uppercase mb-3'>Remaining Staking(L/R) :
            <span class='mb-1 text-dark'> {{ toHumanReadable($member->left_stake_power) }} / {{ toHumanReadable($member->right_stake_power) }}</span>
        </h6>
    </div>
</div>
