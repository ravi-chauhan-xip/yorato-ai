<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CalculateDirectSponsorStakingIncome;
use App\Jobs\UpgradeStakeOnNetwork;
use App\ListBuilders\Admin\StakeCoinListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\StakeCoin;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
use DB;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Throwable;
use Validator;

class StakeController extends Controller
{
    use CoinTrait;

    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return StakeCoinListBuilder::render();
    }

    public function create(Request $request): Renderable
    {
        return view('admin.stake.create');
    }

    public function store(Request $request)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'amount' => 'required',
            'wallet_address' => 'required|without_spaces|exists:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
        ], [
            'amount.required' => 'The amount is required',
        ]);
        $amount = $request->get('amount');
        $member = Member::with('package')->whereHas('user', function ($q) use ($request) {
            return $q->where('wallet_address', $request->get('wallet_address'));
        })->first();

        try {
            return DB::transaction(function () use ($member, $amount) {
                if ($member->status === Member::STATUS_FREE_MEMBER) {
                    return redirect()->back()->with([
                        'error' => 'User not paid',
                    ])->withInput();
                }

                //                WalletTransaction::create([
                //                    'member_id' => $member->id,
                //                    'responsible_type' => Admin::class,
                //                    'responsible_id' => $this->admin->id,
                //                    'opening_balance' => $member->wallet_balance,
                //                    'closing_balance' => $member->wallet_balance + $amount,
                //                    'amount' => $amount,
                //                    'service_charge' => 0,
                //                    'total' => $amount,
                //                    'comment' => 'Admin Credit for stake of '.toHumanReadable($amount).' USDT',
                //                    'type' => WalletTransaction::TYPE_CREDIT,
                //                ]);

                $stake = StakeCoin::create([
                    'member_id' => $member->id,
                    'package_id' => $member->package_id,
                    'amount' => $amount,
                    'capping_days' => settings('capping_days'),
                    'remaining_days' => settings('capping_days'),
                    'status' => StakeCoin::STATUS_ACTIVE,
                    'done_by' => StakeCoin::DONE_BY_ADMIN,
                ]);
                //
                //                $stake->walletTransaction()->create([
                //                    'member_id' => $member->id,
                //                    'opening_balance' => $member->wallet_balance,
                //                    'closing_balance' => $member->wallet_balance - $stake->amount,
                //                    'amount' => $stake->amount,
                //                    'service_charge' => 0,
                //                    'total' => $stake->amount,
                //                    'comment' => 'Debited '.toHumanReadable($stake->amount).' USDT for stake',
                //                    'type' => WalletTransaction::TYPE_DEBIT,
                //                ]);

                CalculateDirectSponsorStakingIncome::dispatch($stake);
                //                UpgradeStakeOnNetwork::dispatch($stake);

                return redirect()->route('admin.stake.index')->with([
                    'success' => 'Staking applied successfully',
                ]);
            });
        } catch (Throwable $e) {
            Log::debug($e->getMessage());

            return redirect()->back()->with([
                'error' => 'Something went wrong. Please try again',
            ])->withInput();
        }
    }
}
