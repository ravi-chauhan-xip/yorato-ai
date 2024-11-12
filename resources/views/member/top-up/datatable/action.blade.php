@if($model->status === \App\Models\StakeCoin::STATUS_ACTIVE
&& today()->diffInDays($model->created_at) <= 60)
    <a href="{{ route('user.stake.withdraw.create',$model) }}" class="btn btn-primary btn-sm" target="_blank">
        Full Withdraw
    </a>
@endif
