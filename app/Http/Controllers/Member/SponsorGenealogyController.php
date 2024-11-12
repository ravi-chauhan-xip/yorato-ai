<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Contracts\Support\Renderable;
use Str;

class SponsorGenealogyController extends Controller
{
    public function sponsorShow($code = null): Renderable
    {
        $member = Member::with('media', 'user', 'sponsor.user')->whereHas('user', function ($q) use ($code) {
            return $q->where('wallet_address', $code);
        })->first();

        if (! $member || ! Str::contains($member->sponsor_path, $this->member->sponsor_path)) {
            $member = Member::with('media', 'user', 'sponsor.user')->find($this->member->id);
        }

        return view('member.sponsor-genealogy.show', ['member' => $member]);
    }
}
