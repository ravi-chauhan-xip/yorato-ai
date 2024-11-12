<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\DirectSponsorStakeIncomeListBuilder;
use App\ListBuilders\Member\DirectWalletIncomeListBuilder;
use App\ListBuilders\Member\LeadershipIncomeListBuilder;
use App\ListBuilders\Member\StakingIncomeListBuilder;
use App\ListBuilders\Member\TeamMatchingStakingIncomeListBuilder;
use App\ListBuilders\Member\TeamMatchingWalletIncomeListBuilder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * @throws Exception
     */
    public function stakingIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return StakingIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    /**
     * @throws Exception
     */
    public function directWalletIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return DirectWalletIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function directSponsorStakeIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return DirectSponsorStakeIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function teamMatchingWalletIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return TeamMatchingWalletIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function teamMatchingStakingIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return TeamMatchingStakingIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function leadershipIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return LeadershipIncomeListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }
}
