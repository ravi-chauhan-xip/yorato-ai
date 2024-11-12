<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Admin\SendMemberBlockedSMS;
use App\Jobs\Admin\SendMemberUnBlockedSMS;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;

class BlockMemberController extends Controller
{
    public function store(Member $member): RedirectResponse
    {
        $member->update([
            'status' => Member::STATUS_BLOCKED,
        ]);

        if (settings('sms_enabled')) {
            SendMemberBlockedSMS::dispatch($member);
        }

        return redirect()->route('admin.users.index')->with('success', 'User blocked successfully');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->activated_at) {
            $status = Member::STATUS_ACTIVE;
        } else {
            $status = Member::STATUS_FREE_MEMBER;
        }

        $member->update([
            'status' => $status,
        ]);

        if (settings('sms_enabled')) {
            SendMemberUnBlockedSMS::dispatch($member);
        }

        return redirect()->route('admin.users.index')->with('success', 'User un-blocked successfully');
    }
}
