<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

class WithdrawalController extends Controller
{
    public function create()
    {
        return view('member.withdrawal.create');
    }

    public function index()
    {
        return view('member.withdrawal.index');
    }
}
