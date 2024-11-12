<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Auth;

class MemberImpersonateController extends Controller
{
    public function store(Member $member)
    {
        if ($member->isBlocked()) {
            return redirect()->route('admin.users.index')->with('error', 'Member is blocked.');
        }

        Auth::shouldUse('member');
        Auth::login($member->user);

        return redirect()->route('user.dashboard.index');
    }
}
