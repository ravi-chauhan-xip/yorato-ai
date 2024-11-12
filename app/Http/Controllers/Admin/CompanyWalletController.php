<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\CompanyWalletListBuilder;
use App\Models\CompanyWallet;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use SWeb3\Accounts;
use Throwable;

class CompanyWalletController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        return CompanyWalletListBuilder::render();
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:company_wallets,name',
            'address' => [
                'required',
                'unique:company_wallets,address',
                'regex:/^0x[a-fA-F0-9]{40}$/',
            ],
            'privateKey' => 'required',
        ], [
            'name.required' => 'The name is required',
            'name.unique' => 'The name already exists',
            'address.required' => 'The wallet address is required',
            'address.regex' => 'The wallet address format is invalid',
            'address.unique' => 'The wallet address already exists',
            'privateKey.required' => 'The private key is required',
        ]);

        try {
            $account = Accounts::privateKeyToAccount($request->input('privateKey'));

            if (strtolower($account->address) !== strtolower($request->input('address'))) {
                return redirect()->route('admin.company-wallet.create')
                    ->withInput()
                    ->with('error', 'Wallet Address and Private Key do not match');
            }
        } catch (Throwable $e) {
            return redirect()->route('admin.company-wallet.create')
                ->withInput()
                ->with('error', 'Please enter a valid Private Key');
        }

        try {
            return DB::transaction(function () use ($request) {
                CompanyWallet::create([
                    'name' => $request->get('name'),
                    'address' => $request->get('address'),
                    'private_key' => $request->get('privateKey'),
                ]);

                return redirect()->route('admin.company-wallet.index')->with(['success' => 'Wallet address created successfully']);
            });
        } catch (Throwable $e) {
            $this->logExceptionAndRespond($e);

            return redirect()->route('admin.company-wallet.create')->withInput()
                ->with('error', 'Please enter valid private key');
        }
    }

    public function create(): Factory|View|Application
    {
        return view('admin.company-wallet.create');
    }

    public function updateStatus(CompanyWallet $companyWallet): RedirectResponse
    {
        if ($companyWallet->status === CompanyWallet::STATUS_ACTIVE) {
            $companyWallet->status = CompanyWallet::STATUS_IN_ACTIVE;
        } else {
            $companyWallet->status = CompanyWallet::STATUS_ACTIVE;
        }
        $companyWallet->save();

        return redirect()->route('admin.company-wallet.index')->with('success', 'Company wallet status change successfully');
    }

    public function updateLockedAt(CompanyWallet $companyWallet): RedirectResponse
    {
        try {
            if ($companyWallet->locked_at) {
                $companyWallet->locked_at = null;
                $status = 'unlocked';
            } else {
                $companyWallet->locked_at = Carbon::now();
                $status = 'locked';
            }

            $companyWallet->save();

            return redirect()->route('admin.company-wallet.index')->with(['success' => "Company wallet $status successfully"]);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }
}
