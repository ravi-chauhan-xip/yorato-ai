<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Auth;

class GenealogyController extends Controller
{
    public function show($wallet = null)
    {
        $member = Member::whereHas('user', function ($q) use ($wallet) {
            return $q->where('wallet_address', $wallet);
        })->first();

        if (! $member || ! $member->isChildOf(Auth::user()->member)) {
            $member = Auth::user()->member;
        }

        $side = null;
        if ($member->id != Auth::user()->member->id) {
            if (Auth::user()->member->left && $member->isChildOf(Auth::user()->member->left)) {
                $side = Member::PARENT_SIDE_LEFT;
            }
            if (Auth::user()->member->right && $member->isChildOf(Auth::user()->member->right)) {
                $side = Member::PARENT_SIDE_RIGHT;
            }
        }

        return view('member.genealogy.show', [
            'member' => $member,
            'side' => $side,
        ]);
    }
}
