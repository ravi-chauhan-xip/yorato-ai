<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\ListBuilders\Member\DepositListBuilder;
use Auth;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DepositController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|RedirectResponse|JsonResponse
    {
        return DepositListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function create(Request $request): Renderable|RedirectResponse
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'amount' => 'required',
            ], [
                'amount.required' => 'The amount is required.',
                //                'amount.integer' => 'Amount must be multiple of 100',
            ]);

            $companyDepositWalletAddress = strtolower(env('USDT_DEPOSIT_WALLET_ADDRESS'));

            $amount = $request->input('amount');

            //            if ($amount % 100 !== 0) {
            //                return redirect()->back()->with('error', 'Amount must be multiple of 100')->withInput();
            //            }

            return view('member.deposits.create', [
                'status' => true,
                'member' => Auth::user()->member,
                'qrImage' => (new \Milon\Barcode\DNS2D)->getBarcodeHTML(
                    $companyDepositWalletAddress, 'QRCODE'
                ),
                'userWallet' => $this->user->wallet_address,
                'companyDepositWalletAddress' => $companyDepositWalletAddress,
                'amount' => $amount,
            ]);
        }

        return view('member.deposits.create', [
            'status' => false,
            'amount' => null,
        ]);
    }
}
