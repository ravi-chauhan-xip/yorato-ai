<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\DirectSponsorStakeIncomeListBuilder;
use App\ListBuilders\Admin\DirectWalletIncomeListBuilder;
use App\ListBuilders\Admin\LeadershipIncomeListBuilder;
use App\ListBuilders\Admin\StakingIncomeListBuilder;
use App\ListBuilders\Admin\TeamMatchingStakingIncomeListBuilder;
use App\ListBuilders\Admin\TeamMatchingWalletIncomeListBuilder;
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
        return StakingIncomeListBuilder::render();
    }

    /**
     * @throws Exception
     */
    public function directWalletIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return DirectWalletIncomeListBuilder::render();
    }

    /**
     * @throws Exception
     */
    public function directSponsorStakeIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return DirectSponsorStakeIncomeListBuilder::render();
    }

    public function teamMatchingWalletIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return TeamMatchingWalletIncomeListBuilder::render();
    }

    public function teamMatchingStakingIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return TeamMatchingStakingIncomeListBuilder::render();
    }

    public function leadershipIncome(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return LeadershipIncomeListBuilder::render();
    }
}
