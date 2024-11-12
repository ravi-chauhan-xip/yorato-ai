<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWalletDeposit;
use App\ListBuilders\Admin\DepositListBuilder;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class DepositController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request): Renderable|RedirectResponse|JsonResponse
    {
        return DepositListBuilder::render();
    }

    public function create(): Renderable|JsonResponse|RedirectResponse
    {
        return view('admin.deposits.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'tx_hash' => 'required|unique:user_deposits,transaction_hash|regex:/^0x([A-Fa-f0-9]{64})$/',
        ], [
            'tx_hash.required' => 'The Tx Hash required',
            'tx_hash.unique' => 'The tx hash has already been taken',
            'tx_hash.regex' => 'The tx hash format is invalid',
        ]);

        try {
            ProcessWalletDeposit::dispatch($request->input('tx_hash'));

            return redirect()->route('admin.deposits.index')->with(['success' => 'Deposit process successfully']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }
}
