<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\StakeCoin;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class UpgradeStakeOnNetwork implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public StakeCoin $stakeCoin;

    public function __construct(StakeCoin $stakeCoin)
    {
        $this->stakeCoin = $stakeCoin;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $separatedParents = $this->stakeCoin->member->getSeparatedParents();

            $leftParentIds = collect(
                $separatedParents['left']
            )->pluck('id');

            $rightParentIds = collect(
                $separatedParents['right']
            )->pluck('id');

            if (count($leftParentIds)) {
                Member::where('status', '=', Member::STATUS_ACTIVE)
                    ->whereIn('id', $leftParentIds)
                    ->increment('left_stake_bv', $this->stakeCoin->amount);
                Member::where('status', '=', Member::STATUS_ACTIVE)
                    ->whereIn('id', $leftParentIds)
                    ->increment('left_stake_power', $this->stakeCoin->amount);
            }
            if (count($rightParentIds)) {
                Member::where('status', '=', Member::STATUS_ACTIVE)
                    ->whereIn('id', $rightParentIds)
                    ->increment('right_stake_bv', $this->stakeCoin->amount);
                Member::where('status', '=', Member::STATUS_ACTIVE)
                    ->whereIn('id', $rightParentIds)
                    ->increment('right_stake_power', $this->stakeCoin->amount);
            }

            foreach ($leftParentIds->merge($rightParentIds)->unique() as $memberId) {
                MatchStakeOnNetwork::dispatch(Member::find($memberId))->afterCommit();
            }

            MatchStakeOnNetwork::dispatch($this->stakeCoin->member)->afterCommit();
        });
    }
}
