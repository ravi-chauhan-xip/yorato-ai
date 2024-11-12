<?php

namespace App\Jobs;

use App\Models\Member;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class AddMemberOnNetwork implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Member $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * Execute the job.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $separatedParents = $this->member->getSeparatedParents();

            $leftParentIds = collect(
                $separatedParents['left']
            )->pluck('id');

            $rightParentIds = collect(
                $separatedParents['right']
            )->pluck('id');

            if (count($leftParentIds)) {
                Member::whereIn('id', $leftParentIds)
                    ->increment('left_count');
            }

            if (count($rightParentIds)) {
                Member::whereIn('id', $rightParentIds)
                    ->increment('right_count');
            }
        });
    }
}
