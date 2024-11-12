<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MatchStakeOnNetwork;
use App\Jobs\MatchTopUpOnNetwork;
use App\Models\AdminBVLog;
use App\Models\Member;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class MemberPowerController extends Controller
{
    /**
     * @throws Exception
     */
    public function topUpPower(Member $member): Renderable|JsonResponse|RedirectResponse
    {
        return view('admin.members.top-up-power', ['member' => $member]);
    }

    public function stakePower(Member $member): Renderable|JsonResponse|RedirectResponse
    {
        return view('admin.members.stake-power', ['member' => $member]);
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function topUpPowerStore(Request $request, Member $member)
    {
        $this->validate($request, [
            'add_bv' => 'required|numeric|min:1',
            'side' => 'required',
        ], [
            'add_bv.required' => 'The Power is required',
            'add_bv.min' => 'Power must be at least 1',
            'side.required' => 'The Side is required',
        ]);

        return \DB::transaction(function () use ($request, $member) {

            if ($request->get('side') == Member::PARENT_SIDE_LEFT) {
                $member->increment('left_bv', $request->get('add_bv'));
                $member->increment('left_power', $request->get('add_bv'));
            } else {
                $member->increment('right_bv', $request->get('add_bv'));
                $member->increment('right_power', $request->get('add_bv'));
            }

            AdminBVLog::create([
                'admin_id' => \Auth::user()->id,
                'member_id' => $member->id,
                'parent_side' => $request->get('side'),
                'bv' => $request->get('add_bv'),
                'type' => AdminBVLog::TYPE_TOP_UP,
            ]);

            MatchTopUpOnNetwork::dispatch($member);

            return redirect()->route('admin.users.topup-power', $member)
                ->with(['success' => 'Topup power updated successfully']);
        });
    }

    public function stakePowerStore(Request $request, Member $member)
    {
        $this->validate($request, [
            'add_bv' => 'required|numeric|min:1',
            'side' => 'required',
        ], [
            'add_bv.required' => 'The Power is required',
            'add_bv.min' => 'Power must be at least 1',
            'side.required' => 'The Side is required',
        ]);

        return \DB::transaction(function () use ($request, $member) {
            if ($request->get('side') == Member::PARENT_SIDE_LEFT) {
                $member->increment('left_stake_bv', $request->get('add_bv'));
                $member->increment('left_stake_power', $request->get('add_bv'));
            } else {
                $member->increment('right_stake_bv', $request->get('add_bv'));
                $member->increment('right_stake_power', $request->get('add_bv'));
            }

            AdminBVLog::create([
                'admin_id' => \Auth::user()->id,
                'member_id' => $member->id,
                'parent_side' => $request->get('side'),
                'bv' => $request->get('add_bv'),
                'type' => AdminBVLog::TYPE_STAKE,
            ]);

            MatchStakeOnNetwork::dispatch($member)->afterCommit();

            return redirect()->route('admin.users.stake-power', $member)
                ->with(['success' => 'Stake power updated successfully']);
        });
    }
}
