<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\CalculateDirectWalletIncome;
use App\Jobs\ProcessWalletDeposit;
use App\Jobs\UpgradeTopUpOnNetwork;
use App\ListBuilders\Member\TopUpListBuilder;
use App\Models\Member;
use App\Models\Package;
use App\Models\StakeCoin;
use App\Models\TopUp;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
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

class TopUpController extends Controller
{
    use CoinTrait;

    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return TopUpListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function create(Request $request): Renderable
    {
        return view('member.top-up.create');
    }

    /**
     * @throws ValidationException
     */
    public function storeValidation(Request $request): JsonResponse
    {
        $this->validate($request, [
            'package_id' => 'required|exists:packages,id',
        ], [
            'package_id.required' => 'The package is required',
            'package_id.exists' => 'The package not found',
        ]);

        $package = Package::where('id', $request->get('package_id'))
            ->first();

        if (TopUp::whereMemberId($this->member->id)->wherePackageId($package->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Package already purchased',
            ]);
        }

        if ($this->member->package_id) {
            if ($this->member->package_id >= $package->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are unable to buy the same package again or downgrade',
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Valid data',
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'walletAddress' => 'required',
            'package_id' => 'required',
            'transactionHash' => 'required',
        ], [
            'amount.required' => 'The amount is required',
        ]);

        $package = Package::where('id', $request->get('package_id'))
            ->first();

        $amount = $package->amount;

        $this->member->load('package');

        $currentPackage = $this->member->package;
        if ($currentPackage) {
            $amount = $package->amount - $currentPackage->amount;
        }

        $transactionHash = $request->get('transactionHash');
        try {
            return DB::transaction(function () use ($package, $request, $transactionHash, $amount) {

                $deposit = UserDeposit::create([
                    'member_id' => $this->member->id,
                    'order_no' => UserDeposit::generateRandomOrderNo(),
                    'package_id' => $package->id,
                    'amount' => $amount,
                    'transaction_hash' => $transactionHash,
                    'from_address' => $request->get('walletAddress'),
                    'to_address' => env('VITE_USDT_DEPOSIT_WALLET_ADDRESS'),
                    'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_PENDING,
                ]);

                TopUp::create([
                    'member_id' => $this->member->id,
                    'package_id' => $package->id,
                    'amount' => $amount,
                    'user_deposit_id' => $deposit->id,
                    'status' => TopUp::STATUS_PENDING,
                ]);

                ProcessWalletDeposit::dispatch($deposit);

                Session::flash('success', 'Topup applied successfully');

                return response()->json([
                    'status' => true,
                    'message' => 'Topup applied successfully',
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

    public function withdrawCreate(StakeCoin $stakeCoin)
    {
        if (today()->diffInDays($stakeCoin->created_at) <= 30) {
            $amount = ($stakeCoin->amount * 65) / 100;
        } elseif (today()->diffInDays($stakeCoin->created_at) <= 60) {
            $amount = ($stakeCoin->amount * 50) / 100;
        } else {
            return redirect()->back()->with('error', 'You have exceeded your full withdraw amount time limit')->withInput();
        }

        $serviceCharge = $amount * settings('service_charge') / 100;

        return view('member.top-up.withdraw-create', [
            'stateCoin' => $stakeCoin,
            'amount' => $amount,
            'serviceCharge' => $serviceCharge,
            'total' => $amount - $serviceCharge,
        ]);
    }

    public function getPackages(): JsonResponse
    {
        $packages = Package::where('status', Package::STATUS_ACTIVE)->get()->map(function (Package $package) {
            return [
                'id' => $package->id,
                'amount' => toHumanReadable($package->amount),
                'name' => $package->name,
            ];
        });

        $this->member->load('package');
        $currentPackage = $this->member->package;

        return response()->json([
            'status' => true,
            'packages' => $packages,
            'currentPackage' => $currentPackage,
        ]);
    }

    public function walletCreate(Request $request): Renderable
    {
        return view('member.top-up.wallet-top-up', ['packages' => Package::where('status', Package::STATUS_ACTIVE)->get()]);
    }

    public function walletStore(Request $request)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'walletAddress' => 'required|without_spaces|exists:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
            'package_id' => 'required|exists:users,id',
        ], [
            'walletAddress.required' => 'The wallet address is required',
            'walletAddress.exists' => 'The wallet address is not exist',
            'walletAddress.regex' => 'The wallet address format is invalid',
            'walletAddress.unique' => 'The wallet address already exists',
            'walletAddress.without_spaces' => 'The wallet address cannot contain white spaces',
            'package_id.required' => 'The package Id is required',
            'package_id.exists' => 'The package Id is not exist',
        ]);

        $package = Package::where('id', $request->get('package_id'))
            ->first();

        $amount = $package->amount;

        $member = Member::with('package')->whereHas('user', function ($q) use ($request) {
            return $q->where('wallet_address', $request->get('walletAddress'));
        })->first();

        if (TopUp::whereMemberId($member->id)->wherePackageId($package->id)->exists()) {
            return redirect()->back()->with([
                'error' => 'Package already purchased',
            ]);
        }

        if ($member->package_id) {
            if ($member->package_id >= $package->id) {
                return redirect()->back()->with([
                    'error' => 'You are unable to buy the same package again or downgrade',
                ]);
            }
        }

        $currentPackage = $member->package;
        if ($currentPackage) {
            $amount = $package->amount - $currentPackage->amount;
        }

        if ($this->member->wallet_balance < $amount) {
            return redirect()->back()->with([
                'error' => 'You have not sufficient balance for this transaction',
            ]);
        }

        try {
            return DB::transaction(function () use ($package, $amount, $member) {

                $topUp = TopUp::create([
                    'member_id' => $member->id,
                    'from_member_id' => $this->member->id,
                    'package_id' => $package->id,
                    'amount' => $amount,
                    'status' => TopUp::STATUS_SUCCESS,
                    'done_by' => TopUp::DONE_BY_MEMBER,
                ]);

                if ($member !== $this->member->id) {
                    $comment = 'Debited '.toHumanReadable($topUp->amount).' USDT for Top Up of '.$topUp->package->name.' for '.$member->user->wallet_address;
                } else {
                    $comment = 'Debited '.toHumanReadable($topUp->amount).' USDT for Top Up of '.$topUp->package->name;
                }

                $topUp->walletTransaction()->create([
                    'member_id' => $this->member->id,
                    'opening_balance' => $this->member->wallet_balance,
                    'closing_balance' => $this->member->wallet_balance - $topUp->amount,
                    'amount' => $topUp->amount,
                    'service_charge' => 0,
                    'total' => $topUp->amount,
                    'comment' => $comment,
                    'type' => WalletTransaction::TYPE_DEBIT,
                ]);

                if ($topUp->member->isFree()) {
                    $topUp->member->update([
                        'is_paid' => Member::IS_PAID,
                        'status' => Member::STATUS_ACTIVE,
                        'activated_at' => now(),
                    ]);
                }

                $topUp->member->update(['package_id' => $topUp->package_id]);

                CalculateDirectWalletIncome::dispatch($topUp);
                UpgradeTopUpOnNetwork::dispatch($topUp);

                return redirect()->route('user.top-up.show')->with([
                    'success' => 'Topup applied successfully',
                ]);
            });
        } catch (Throwable $e) {
            Log::debug($e->getMessage());

            return redirect()->back()->with([
                'error' => 'Something went wrong . Please try again',
            ]);
        }
    }
}
