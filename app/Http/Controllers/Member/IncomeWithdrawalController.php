<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWalletWithdrawals;
use App\ListBuilders\IncomeWithdrawalRequestListBuilder;
use App\Models\IncomeWithdrawalRequest;
use App\Models\Member;
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
use Sentry;
use Throwable;

class IncomeWithdrawalController extends Controller
{
    use CoinTrait;

    /**
     * @throws Exception
     */
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        return IncomeWithdrawalRequestListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:'.settings('min_withdrawal_limit'),
        ], [
            'amount.min' => 'The withdrawal request must be equal to or greater than '.settings('min_withdrawal_limit').' '.env('APP_CURRENCY_USDT'),
            'amount.required' => 'The amount is required',
            'amount.numeric' => 'The amount must be a number',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $member = Member::whereId($this->member->id)
                    ->lockForUpdate()
                    ->first();

                if ($member->status === Member::STATUS_FREE_MEMBER) {
                    return redirect()->back()->with('error', 'Member not paid or active')->withInput();
                }

                if (
                    $member->incomeWithdrawalRequests()
                        ->whereNotIn('status', [
                            IncomeWithdrawalRequest::STATUS_COMPLETED,
                        ])
                        ->lockForUpdate()
                        ->exists()
                ) {
                    return redirect()->back()->with('error', 'You already have a withdrawal request in process')->withInput();
                }

                if (! $member->user->wallet_address) {
                    return redirect()->back()->with('error', 'Member have no withdrawal wallet address')->withInput();
                }

                $amount = $request->get('amount');

                $serviceCharge = $amount * settings('service_charge') / 100;

                if ($member->wallet_balance < $amount) {
                    return redirect()->back()->with('error', 'You do not have enough balance for this withdrawal request')->withInput();
                }

                $total = $amount - $serviceCharge;
                $withdrawalRequest = IncomeWithdrawalRequest::create([
                    'member_id' => $member->id,
                    'to_address' => strtolower($member->user->wallet_address),
                    'amount' => $amount,
                    'service_charge' => $serviceCharge,
                    'total' => $total,
                    'remark' => 'Member withdraw of USDT '.$amount,
                ]);

                WalletTransaction::create([
                    'member_id' => $member->id,
                    'opening_balance' => $member->wallet_balance,
                    'closing_balance' => $member->wallet_balance - $amount,
                    'amount' => $amount,
                    'admin_charge' => 0.00,
                    'total' => $amount,
                    'type' => WalletTransaction::TYPE_DEBIT,
                    'responsible_id' => $withdrawalRequest->id,
                    'responsible_type' => IncomeWithdrawalRequest::class,
                    'comment' => "Withdrawal Request of $withdrawalRequest->amount",
                ]);

                ProcessWalletWithdrawals::dispatch($withdrawalRequest);

                return redirect()->route('user.income-withdrawals.index')->with(['success' => 'Your withdrawal request sent successfully']);
            }, 5);
        } catch (Throwable $e) {
            Sentry::captureException($e);

            return redirect()->back()->with('error', 'Something went wrong. Please try again')->withInput();
        }

    }

    public function create()
    {
        return view('member.income-withdrawal.create');
    }

    /**
     * @throws MathException
     * @throws RoundingNecessaryException
     */
    public function calculation(Request $request): JsonResponse
    {
        if ($request->input('amount')) {
            $amount = $request->input('amount');
            $serviceCharge = $amount * settings('service_charge') / 100;

            return response()->json([
                'status' => true,
                'amount' => toHumanReadable($amount) ?? null,
                'serviceCharge' => toHumanReadable($serviceCharge) ?? null,
                'totalTRS' => toHumanReadable($amount - $serviceCharge) ?? null,
            ]);
        }

        return response()->json([
            'status' => false,
        ]);
    }
}
