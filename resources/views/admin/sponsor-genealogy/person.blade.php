<div class="person mb-2">
    @if($member)
        <div tabindex="0" data-bs-html="true"
             data-bs-container="body"
             title="{{ walletAddress($member->user->wallet_address) }}" data-bs-toggle="popover" data-bs-trigger="{{ Agent::isMobile() ? 'focus' : 'hover'}}"
             data-bs-original-title="{{ $member->code }}"
             data-bs-content="@include('admin.sponsor-genealogy.popover', ['member' => $member])">
            @if(Agent::isMobile())
                <img src="{{ $member->present()->genealogyImage() }}"
                     alt="{{ $member->user->wallet_address }}"
                     style="background-color: {{ $member->present()->genealogyImageBackground() }};">
            @else
                <a href="{{ route('admin.sponsor-genealogy.show', $member->user->wallet_address) }}">
                    <img src="{{ $member->present()->genealogyImage() }}"
                         alt="{{ $member->user->wallet_address }}"
                         style="background-color: {{ $member->present()->genealogyImageBackground() }};">
                </a>
            @endif
        </div>
        <p class="name">
            @if(Agent::isMobile())
                <span>
                    <a href="{{ route('admin.sponsor-genealogy.show', $member->user->wallet_address) }}">{{ walletAddress($member->user->wallet_address) }}</a>
                </span>
            @else
                <span>@include('admin.web3-address', ['address' => $member->user->wallet_address])</span>
            @endif
        </p>
    @else
        <a href="javascript:void(0)" >
            <img src="{{ asset('images/blank.svg') }}"
                 alt="Empty" class="" style="background-color: gray">
        </a>
        <p class="name">
            Empty
        </p>
    @endif
</div>
