<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;

class SponsorGenealogyController extends Controller
{
    public function show($code = null)
    {
        $member = Member::with('media', 'user', 'sponsor.user')->whereHas('user', function ($q) use ($code) {
            return $q->where('wallet_address', $code);
        })->first();

        if (! $member) {
            /** @var Member $member */
            $member = Member::with('media', 'user', 'sponsor.user')->first();
        }

        return view('admin.sponsor-genealogy.show', compact('member'));
    }
}
