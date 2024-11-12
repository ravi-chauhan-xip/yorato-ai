<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\BuyCoinListBuilder;
use App\Models\BuyCoin;
use App\Models\Member;
use App\Models\TreasureWalletTransaction;
use App\Models\WalletTransaction;
use App\Traits\CoinTrait;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class BuyCoinController extends Controller
{
    use CoinTrait;

    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|JsonResponse|RedirectResponse
    {
        return BuyCoinListBuilder::render([
            'member_id' => $this->member->id,
        ], 'Swap');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|integer|min:100',
        ], [
            'amount.min' => 'The amount must be at least 100 '.env('APP_CURRENCY_USDT'),
            'amount.required' => 'The amount is required',
            'amount.integer' => 'Amount must be multiple of 100',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                $member = Member::whereId($this->member->id)
                    ->lockForUpdate()
                    ->first();

                $amount = $request->input('amount');

                if ($amount % 100 !== 0) {
                    return redirect()->back()->with('error', 'Amount must be multiple of 100')->withInput();
                }

                if (! $coins = $this->calculateDollarCoins($amount)) {
                    return redirect()->back()->with('error', 'We could not fetch price of '.env('APP_CURRENCY').'. Please try again')->withInput();
                }

                //                if ($member->status != Member::STATUS_ACTIVE) {
                //                    return redirect()->back()->with('error', 'Member not active')->withInput();
                //                }

                if ($member->wallet_balance < $amount) {
                    return redirect()->back()->with('error', 'Insufficient '.env('APP_CURRENCY_USDT').' wallet balance')->withInput();
                }

                if (coinPrice() == null) {
                    return redirect()->back()->with('error', 'Enable to fetch live price.')->withInput();
                }

                $buyCoin = BuyCoin::create([
                    'member_id' => $member->id,
                    'amount' => $coins,
                    'coin_price' => coinPrice(),
                    'dollar_amount' => $amount,
                ]);

                $buyCoin->walletTransactions()->create([
                    'member_id' => $member->id,
                    'opening_balance' => $member->wallet_balance,
                    'closing_balance' => $member->wallet_balance - $buyCoin->dollar_amount,
                    'amount' => $buyCoin->dollar_amount,
                    'service_charge' => 0,
                    'total' => $buyCoin->dollar_amount,
                    'comment' => $buyCoin->dollar_amount.' '.env('APP_CURRENCY_USDT').' swap to '.env('APP_CURRENCY').' coins',
                    'type' => WalletTransaction::TYPE_DEBIT,
                ]);

                $buyCoin->treasureWalletTransactions()->create([
                    'member_id' => $member->id,
                    'opening_balance' => $member->treasure_wallet_balance,
                    'closing_balance' => $member->treasure_wallet_balance + $buyCoin->amount,
                    'amount' => $buyCoin->amount,
                    'dollar_amount' => $buyCoin->dollar_amount,
                    'service_charge' => 0,
                    'total' => $buyCoin->amount,
                    'comment' => toHumanReadable($buyCoin->amount).' credited for '.env('APP_CURRENCY').' wallet balance',
                    'type' => TreasureWalletTransaction::TYPE_CREDIT,
                ]);

                return redirect()->route('user.buy-coins.index')->with('success', 'Coin swap successfully')->withInput();

            }, 5);

        } catch (Throwable $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again')->withInput();
        }
    }

    public function create()
    {
        return view('member.buy-coins.create');
    }

    public function calculation(Request $request): JsonResponse
    {
        $coins = $this->calculateDollarCoins($request->input('amount'));

        return response()->json([
            'status' => true,
            'coins' => toHumanReadable($coins) ?? null,
        ]);
    }
}
