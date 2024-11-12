<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\CalculateDirectSponsorStakingIncome;
use App\Jobs\ProcessWalletDeposit;
use App\Jobs\UpgradeStakeOnNetwork;
use App\ListBuilders\Member\StakeCoinListBuilder;
use App\Models\Member;
use App\Models\Package;
use App\Models\StakeCoin;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use DB;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Log;
use Session;
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
        return StakeCoinListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function create(Request $request): Renderable
    {
        return view('member.stake.create');
    }

    /**
     * @throws ValidationException
     */
    public function storeValidation(Request $request): JsonResponse
    {
        $this->validate($request, [
            'amount' => 'required',
        ], [
            'amount.required' => 'The amount is required',
        ]);

        if ($this->member->status === Member::STATUS_FREE_MEMBER) {
            return response()->json([
                'status' => false,
                'message' => 'User not paid',
            ]);
        }

        $package = Package::where('id', $this->member->package_id)
            ->first();

        if ($package->staking_max > 0) {
            $stakeMax = $package->staking_max;
        } else {
            $stakeMax = INF;
        }

        if ($request->get('amount') < $package->staking_min
            || $request->get('amount') > $stakeMax
        ) {
            return response()->json([
                'status' => false,
                'message' => 'you must stake between '.toHumanReadable($package->staking_min).' to '.toHumanReadable($package->staking_max),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Valid data',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'transactionHash' => 'required',
        ], [
            'amount.required' => 'The amount is required',
        ]);

        $amount = $request->get('amount');
        $transactionHash = $request->get('transactionHash');
        try {
            return DB::transaction(function () use ($request, $transactionHash, $amount) {
                if ($this->member->status === Member::STATUS_FREE_MEMBER) {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not paid',
                    ]);
                }

                $deposit = UserDeposit::create([
                    'member_id' => $this->member->id,
                    'order_no' => UserDeposit::generateRandomOrderNo(),
                    'package_id' => $this->member->package_id,
                    'amount' => $amount,
                    'transaction_hash' => $transactionHash,
                    'from_address' => $request->get('walletAddress'),
                    'to_address' => env('VITE_USDT_DEPOSIT_WALLET_ADDRESS'),
                    'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_PENDING,
                ]);

                StakeCoin::create([
                    'member_id' => $this->member->id,
                    'user_deposit_id' => $deposit->id,
                    'package_id' => $this->member->package_id,
                    'amount' => $amount,
                    'capping_days' => settings('capping_days'),
                    'remaining_days' => settings('capping_days'),
                    'status' => StakeCoin::STATUS_PENDING,
                ]);

                ProcessWalletDeposit::dispatch($deposit);

                Session::flash('success', 'Staking applied successfully');

                return response()->json([
                    'status' => true,
                    'message' => 'Staking applied successfully',
                ]);
            });
        } catch (Throwable $e) {
            Log::debug($e->getMessage());

            return response()->json([
                'status' => true,
                'message' => 'Something went wrong. Please try again',
            ]);
        }
    }

    /**
     * @throws MathException
     * @throws RoundingNecessaryException
     */
    public function calculation(Request $request): JsonResponse
    {
        $dollarAmount = $this->calculateCoinsDollar($request->input('amount'));

        return response()->json([
            'status' => true,
            'dollarAmount' => toHumanReadable($dollarAmount) ?? null,
        ]);
    }

    public function walletCreate(Request $request): Renderable
    {
        return view('member.stake.wallet-create');
    }

    public function walletStore(Request $request)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'amount' => 'required',
            'walletAddress' => 'required|without_spaces|exists:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
        ], [
            'walletAddress.required' => 'The wallet address is required',
            'walletAddress.exists' => 'The wallet address is not exist',
            'walletAddress.regex' => 'The wallet address format is invalid',
            'walletAddress.unique' => 'The wallet address already exists',
            'walletAddress.without_spaces' => 'The wallet address cannot contain white spaces',
            'amount.required' => 'The amount is required',
        ]);

        $amount = $request->get('amount');
        $member = Member::with('package')->whereHas('user', function ($q) use ($request) {
            return $q->where('wallet_address', $request->get('walletAddress'));
        })->first();

        if ($this->member->wallet_balance < $amount) {
            return redirect()->back()->with([
                'error' => 'You have not sufficient balance for this transaction',
            ]);
        }

        try {
            return DB::transaction(function () use ($member, $amount) {
                if ($member->status === Member::STATUS_FREE_MEMBER) {
                    redirect()->back()->with([
                        'error' => 'User not paid',
                    ])->withInput();
                }

                $stake = StakeCoin::create([
                    'member_id' => $member->id,
                    'from_member_id' => $this->member->id,
                    'package_id' => $member->package_id,
                    'amount' => $amount,
                    'capping_days' => settings('capping_days'),
                    'remaining_days' => settings('capping_days'),
                    'status' => StakeCoin::STATUS_ACTIVE,
                    'done_by' => StakeCoin::DONE_BY_MEMBER,
                ]);

                if ($member !== $this->member->id) {
                    $comment = 'Debited '.toHumanReadable($stake->amount).' USDT for stake of '.$member->user->wallet_address;
                } else {
                    $comment = 'Debited '.toHumanReadable($stake->amount).' USDT for stake';
                }

                $stake->walletTransaction()->create([
                    'member_id' => $this->member->id,
                    'opening_balance' => $this->member->wallet_balance,
                    'closing_balance' => $this->member->wallet_balance - $stake->amount,
                    'amount' => $stake->amount,
                    'service_charge' => 0,
                    'total' => $stake->amount,
                    'comment' => $comment,
                    'type' => WalletTransaction::TYPE_DEBIT,
                ]);

                CalculateDirectSponsorStakingIncome::dispatch($stake);
                UpgradeStakeOnNetwork::dispatch($stake);

                return redirect()->route('user.stake.show')->with([
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
