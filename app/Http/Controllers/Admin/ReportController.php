<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\AdminBVListBuilder;
use App\ListBuilders\Admin\MostActiveMemberListBuilder;
use App\ListBuilders\Admin\TopEarnersListBuilder;
use App\ListBuilders\Admin\TopUpListBuilder;
use App\ListBuilders\Member\MemberLevelDetailListBuilder;
use App\Models\Member;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    /**
     * @throws Exception
     */
    public function mostActiveMember(): Renderable|JsonResponse|RedirectResponse
    {
        return MostActiveMemberListBuilder::render();
    }

    /**
     * @throws Exception
     */
    public function topEarners(): Renderable|JsonResponse|RedirectResponse
    {
        return TopEarnersListBuilder::render();
    }

    public function topUp(): Renderable|JsonResponse|RedirectResponse
    {
        return TopUpListBuilder::render();
    }

    /**
     * @throws ValidationException
     */
    public function level(Request $request)
    {
        if ($request->method() == 'POST') {
            $this->validate($request, [
                'walletAddress' => 'required',
            ], [
                'walletAddress.required' => 'wallet address is required',
            ]);

            $member = Member::with('user')
                ->whereHas('user', function ($q) use ($request) {
                    return $q->where('wallet_address', $request->input('walletAddress'));
                })->first();

            if (! $member) {
                return view('admin.reports.level', [
                    'levelDetails' => 0,
                    'walletAddress' => null,
                ]);
            }

            $level = 1;

            do {
                $teamCount = Member::where('level', $level + $member->level)
                    ->where('sponsor_path', 'like', $member->sponsor_path.'/%')
                    ->count();

                $details[] = [
                    'id' => $level,
                    'level' => $level,
                    'teamCount' => $teamCount,
                ];

                $level++;
            } while ($level <= 25);

            return view('admin.reports.level', [
                'levelDetails' => $details,
                'walletAddress' => $request->input('walletAddress'),
            ]);
        }

        return view('admin.reports.level', [
            'walletAddress' => null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function memberLevelDetail(Request $request, $walletAddress = null, $level = 0): Renderable|JsonResponse|RedirectResponse
    {
        if ($request->get('level')) {
            $level = (int) $request->get('level');
        }

        if ($walletAddress) {
            $member = Member::with('user')
                ->whereHas('user', function ($q) use ($walletAddress) {
                    return $q->where('wallet_address', $walletAddress);
                })->first();
        } else {
            $member = Member::orderBy('id', 'asc')->first();
        }

        return MemberLevelDetailListBuilder::render([
            'path' => $member->sponsor_path,
            'memberLevel' => $member->level,
            'level' => $level,
        ],
            name: ($level ? ' Level '.$level.' Team' : '')
        );
    }

    public function adminBv()
    {
        return AdminBVListBuilder::render();
    }
}
