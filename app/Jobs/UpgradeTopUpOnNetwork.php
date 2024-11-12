<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\TopUp;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class UpgradeTopUpOnNetwork implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public TopUp $topUp;

    public function __construct(TopUp $topUp)
    {
        $this->topUp = $topUp;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $separatedParents = $this->topUp->member->getSeparatedParents();

            $leftParentIds = collect(
                $separatedParents['left']
            )->pluck('id');

            $rightParentIds = collect(
                $separatedParents['right']
            )->pluck('id');

            if (count($leftParentIds)) {
                Member::where('status', Member::STATUS_ACTIVE)
                    ->whereIn('id', $leftParentIds)
                    ->increment('left_bv', $this->topUp->amount);
                Member::where('status', Member::STATUS_ACTIVE)
                    ->whereIn('id', $leftParentIds)
                    ->increment('left_power', $this->topUp->amount);
            }
            if (count($rightParentIds)) {
                Member::where('status', Member::STATUS_ACTIVE)
                    ->whereIn('id', $rightParentIds)
                    ->increment('right_bv', $this->topUp->amount);
                Member::where('status', Member::STATUS_ACTIVE)
                    ->whereIn('id', $rightParentIds)
                    ->increment('right_power', $this->topUp->amount);
            }

            foreach ($leftParentIds->merge($rightParentIds)->unique() as $memberId) {
                MatchTopUpOnNetwork::dispatch(Member::find($memberId))->afterCommit();
            }

            MatchTopUpOnNetwork::dispatch($this->topUp->member)->afterCommit();
        });
    }
}
