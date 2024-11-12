<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Admin\SendAdminCreditDebitSms;
use App\Jobs\Admin\SendAdminDebitSms;
use App\ListBuilders\Admin\WalletTransactionListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\WalletTransaction;
use Auth;
use DB;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class WalletTransactionController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return WalletTransactionListBuilder::render();
    }

    public function create(): Renderable|RedirectResponse
    {
        return view('admin.wallet-transactions.create', [
            'types' => WalletTransaction::TYPES,
            'recentTransactions' => WalletTransaction::whereResponsibleType(Admin::class)
                ->with(['member.user'])
                ->latest('id')
                ->take(10)
                ->get(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:'.implode(',', array_keys(WalletTransaction::TYPES)),
            'code' => [
                'required',
                'regex:/^0x[a-fA-F0-9]{40}$/',
                function ($attribute, $value, $fail) {
                    if (! Member::with('user')->whereHas('user', function ($q) use ($value) {
                        return $q->where('wallet_address', $value);
                    })->notBlocked()->exists()) {
                        $fail('Wallet address is invalid or blocked');
                    }
                },
            ],
        ], [
            'code.required' => 'Wallet address is required',
            'code.regex' => 'Wallet address format is invalid',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $amount = $request->get('amount');
                $member = Member::with('user')->whereHas('user', function ($q) use ($request) {
                    return $q->where('wallet_address', $request->get('code'));
                })->first();

                if ($request->get('type') == WalletTransaction::TYPE_DEBIT) {
                    if ($member->wallet_balance < $amount) {
                        return redirect()->back()
                            ->with(['error' => 'Member does not have enough balance for this transaction'])
                            ->withInput();
                    } else {
                        $type = WalletTransaction::TYPE_DEBIT;
                        $closingBalance = $member->wallet_balance - $amount;
                        $successMessage = 'Wallet debited successfully';
                    }
                } else {
                    $type = WalletTransaction::TYPE_CREDIT;
                    $closingBalance = $member->wallet_balance + $amount;
                    $successMessage = 'Wallet credited successfully';
                }

                $transaction = $member->walletTransactions()->create([
                    'member_id' => $member->id,
                    'opening_balance' => $member->wallet_balance,
                    'closing_balance' => $closingBalance,
                    'amount' => $amount,
                    'tds' => 0.00,
                    'admin_charge' => 0.00,
                    'total' => $amount,
                    'type' => $type,
                    'responsible_id' => Auth::user()->id,
                    'responsible_type' => Admin::class,
                    'comment' => $request->get('comment'),
                ]);

                if ($type === WalletTransaction::TYPE_CREDIT) {
                    if (settings('sms_enabled')) {
                        SendAdminCreditDebitSms::dispatch($transaction->id);
                    }
                } else {
                    if (settings('sms_enabled')) {
                        SendAdminDebitSms::dispatch($transaction->id);
                    }
                }

                return redirect()->back()->with(['success' => $successMessage]);
            });
        } catch (Throwable $e) {
            dd($e);

            return $this->logExceptionAndRespond($e);
        }
    }
}
