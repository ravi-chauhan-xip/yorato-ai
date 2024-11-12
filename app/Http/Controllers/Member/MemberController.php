<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberController extends Controller
{
    public function show(Member $member): Member
    {
        return $member->load('user');
    }
}
