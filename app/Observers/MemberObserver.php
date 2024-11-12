<?php

namespace App\Observers;

use App\Models\Member;
use Auth;

class MemberObserver
{
    /**
     * Handle the member "created" event.
     *
     * @return void
     */
    public function created(Member $member)
    {
        //        $member->kyc()->create([]);

        //        if (! $member->code) {
        //            do {
        //                $code = (string) mt_rand(11111111, 99999999);
        //            } while (Member::whereCode($code)->exists());
        //
        //            $member->code = $code;
        //        }

        if ($member->parent) {
            // Save member's path appending to the parent's path
            $member->path = $member->parent->path.'/'.$member->id;

            // Save the member's id on parent's left or right side
            if ($member->parent_side == Member::PARENT_SIDE_LEFT) {
                $member->parent->left_id = $member->id;
            } else {
                $member->parent->right_id = $member->id;
            }

            $member->parent->save();
        }

        // Increment the count of members sponsored by this member's sponsor
        if ($member->sponsor) {
            // Save member's sponsor path appending to the sponsor's path
            $member->sponsor_path = $member->sponsor->sponsor_path.'/'.$member->id;
            $member->sponsor->increment('sponsored_count');

            if ($member->parent_side == Member::PARENT_SIDE_LEFT) {
                $member->sponsor->increment('sponsored_left');
            } else {
                $member->sponsor->increment('sponsored_right');
            }
        }

        $member->save();
    }

    public function updated(Member $member)
    {
        if ($member->isDirty('status') && $member->getOriginal('status')) {
            $member->memberStatusLog()->create([
                'admin_user_id' => Auth::user() && Auth::user()->hasRole('admin') ? Auth::user()->id : null,
                'last_status' => $member->getOriginal('status'),
                'new_status' => $member->status,
            ]);
        }
    }
}
