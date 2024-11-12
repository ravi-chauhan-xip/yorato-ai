<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\CalculateDirectWalletIncome;
use App\Jobs\UpgradeTopUpOnNetwork;
use App\ListBuilders\Admin\TopUpListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Package;
use App\Models\TopUp;
use App\Models\WalletTransaction;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Log;
use Throwable;
use Validator;

class TopUpController extends Controller
{
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        return TopUpListBuilder::render();
    }

    public function create(Request $request): Renderable
    {
        return view('admin.topup.create', ['packages' => Package::where('status', Package::STATUS_ACTIVE)->get()]);
    }

    public function store(Request $request)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'wallet_address' => 'required|without_spaces|exists:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
            'package_id' => 'required|exists:users,id',
            [
                'wallet_address.required' => 'The wallet address is required',
                'wallet_address.exists' => 'The wallet address is not exist',
                'wallet_address.regex' => 'The wallet address format is invalid',
                'wallet_address.unique' => 'The wallet address already exists',
                'wallet_address.without_spaces' => 'The wallet address cannot contain white spaces',
                'package_id.required' => 'The package Id is required',
                'package_id.exists' => 'The package Id is not exist',
            ],
        ]);

        $package = Package::where('id', $request->get('package_id'))
            ->first();

        $amount = $package->amount;

        $member = Member::with('package')->whereHas('user', function ($q) use ($request) {
            return $q->where('wallet_address', $request->get('wallet_address'));
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

        try {
            return DB::transaction(function () use ($package, $amount, $member) {
                //                WalletTransaction::create([
                //                    'member_id' => $member->id,
                //                    'responsible_type' => Admin::class,
                //                    'responsible_id' => $this->admin->id,
                //                    'opening_balance' => $member->wallet_balance,
                //                    'closing_balance' => $member->wallet_balance + $amount,
                //                    'amount' => $amount,
                //                    'service_charge' => 0,
                //                    'total' => $amount,
                //                    'comment' => 'Admin Credit for Topup of '.toHumanReadable($amount).' USDT for package '.$package->name,
                //                    'type' => WalletTransaction::TYPE_CREDIT,
                //                ]);

                $topUp = TopUp::create([
                    'member_id' => $member->id,
                    'package_id' => $package->id,
                    'amount' => $amount,
                    'status' => TopUp::STATUS_SUCCESS,
                    'done_by' => TopUp::DONE_BY_ADMIN,
                ]);

                //                $topUp->walletTransaction()->create([
                //                    'member_id' => $member->id,
                //                    'opening_balance' => $member->wallet_balance,
                //                    'closing_balance' => $member->wallet_balance - $topUp->amount,
                //                    'amount' => $topUp->amount,
                //                    'service_charge' => 0,
                //                    'total' => $topUp->amount,
                //                    'comment' => 'Debited '.toHumanReadable($topUp->amount).' USDT for package '.$topUp->package->name,
                //                    'type' => WalletTransaction::TYPE_DEBIT,
                //                ]);

                if ($topUp->member->isFree()) {
                    $topUp->member->update([
                        'is_paid' => Member::IS_PAID,
                        'status' => Member::STATUS_ACTIVE,
                        'activated_at' => now(),
                    ]);
                }

                $topUp->member->update(['package_id' => $topUp->package_id]);

                CalculateDirectWalletIncome::dispatch($topUp);
                //                UpgradeTopUpOnNetwork::dispatch($topUp);

                return redirect()->route('admin.reports.top-up')->with([
                    'success' => 'Topup applied successfully',
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
