<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class GenealogyController extends Controller
{
    public function show($wallet = null): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $member = Member::whereHas('user', function ($q) use ($wallet) {
            return $q->where('wallet_address', $wallet);
        })->first();

        if (! $member) {
            /** @var Member $member */
            $member = Member::first();
        }

        return view('admin.genealogy.show', compact('member'));
    }
}
