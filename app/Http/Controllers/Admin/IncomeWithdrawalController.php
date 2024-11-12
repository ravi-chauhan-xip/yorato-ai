<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\IncomeWithdrawalRequestExport;
use App\FilterBuilders\Admin\WithdrawalRequestFilterBuilder;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessWalletWithdrawals;
use App\Library\Exporter;
use App\ListBuilders\Admin\IncomeWithdrawalRequestListBuilder;
use App\Models\IncomeWithdrawalRequest;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class IncomeWithdrawalController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        return IncomeWithdrawalRequestListBuilder::render();
    }

    public function list(Request $request)
    {
        if ($request->ajax() || $request->has('export')) {

            if ($request->ajax()) {
                return DataTables::of(WithdrawalRequestFilterBuilder::query())
                    ->editColumn('created_at', function (IncomeWithdrawalRequest $orderProduct) {
                        return $orderProduct->created_at->dateTimeFormat();
                    })
                    ->editColumn('walletAddress', function (IncomeWithdrawalRequest $orderProduct) {
                        if ($orderProduct->member->user->wallet_address) {
                            return view('admin.web3-address', [
                                'address' => $orderProduct->member->user->wallet_address,
                            ]);
                        } else {
                            return '--';
                        }
                    })
                    ->editColumn('fromAddress', function (IncomeWithdrawalRequest $orderProduct) {
                        if ($orderProduct->from_address) {
                            return view('admin.web3-address', [
                                'address' => $orderProduct->from_address,
                            ]);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->editColumn('toAddress', function (IncomeWithdrawalRequest $orderProduct) {
                        if ($orderProduct->from_address) {
                            return view('admin.web3-address', [
                                'address' => $orderProduct->to_address,
                            ]);
                        } else {
                            return 'N/A';
                        }
                    })
                    ->editColumn('amount', function (IncomeWithdrawalRequest $orderProduct) {
                        return toHumanReadable($orderProduct->amount);
                    })
                    ->editColumn('service_charge', function (IncomeWithdrawalRequest $orderProduct) {
                        return toHumanReadable($orderProduct->service_charge);
                    })
                    ->editColumn('total', function (IncomeWithdrawalRequest $orderProduct) {
                        return toHumanReadable($orderProduct->total);
                    })
                    ->editColumn('error', function (IncomeWithdrawalRequest $orderProduct) {
                        return $orderProduct->error ? $orderProduct->error : 'N/A';
                    })
                    ->addColumn('checkbox', 'admin.income-withdrawal.datatable.checkbox')
                    ->addColumn('action', 'admin.income-withdrawal.datatable.action')
                    ->addColumn('status', 'admin.income-withdrawal.datatable.status')
                    ->addColumn('blockchain_status', 'admin.income-withdrawal.datatable.blockchain-status')
                    ->addColumn('tx_hash', 'admin.income-withdrawal.datatable.tx-hash')
                    ->with('totalWithdrawal', toHumanReadable(WithdrawalRequestFilterBuilder::query()->sum('amount')))
                    ->rawColumns(['action', 'status', 'blockchain_status', 'tx_hash', 'checkbox'])
                    ->addIndexColumn()
                    ->toJson();
            }

            if ($request->has('export')) {
                return Exporter::create(IncomeWithdrawalRequestExport::class, WithdrawalRequestFilterBuilder::class)
                    ->usingFilePrefix('IncomeWithdrawalRequests')
                    ->redirect();
            }
        }

        return view('admin.income-withdrawal.index', [
            'statuses' => IncomeWithdrawalRequest::STATUSES,
            'blockChainStatus' => IncomeWithdrawalRequest::BLOCKCHAIN_STATUSES,
        ]);
    }

    public function retryTransfer(IncomeWithdrawalRequest $incomeWithdrawalRequest): JsonResponse|RedirectResponse
    {
        try {
            if ($incomeWithdrawalRequest->blockchain_status !== IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_FAILED) {
                return redirect()->route('admin.income-withdrawal-requests.index')->with(['success' => 'Withdrawal is already in process or processed']);
            }

            $incomeWithdrawalRequest->status = IncomeWithdrawalRequest::STATUS_PROCESSING;
            $incomeWithdrawalRequest->blockchain_status = IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PENDING;
            $incomeWithdrawalRequest->error = null;
            $incomeWithdrawalRequest->save();

            ProcessWalletWithdrawals::dispatch($incomeWithdrawalRequest);

            return redirect()->route('admin.income-withdrawal-requests.index')->with(['success' => 'Retry transfer fund successfully']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    public function statusChange(Request $request)
    {
        if ($request->get('change_status') == null) {
            return redirect()->back()->with(['error' => 'Please select withdrawal request']);
        }

        if (count($request->get('change_status')) > 0) {
            foreach ($request->get('change_status') as $id) {
                $incomeWithdrawalRequest = IncomeWithdrawalRequest::find($id);
                if ($incomeWithdrawalRequest) {

                    $incomeWithdrawalRequest->status = IncomeWithdrawalRequest::STATUS_PROCESSING;
                    $incomeWithdrawalRequest->blockchain_status = IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PENDING;
                    $incomeWithdrawalRequest->error = null;
                    $incomeWithdrawalRequest->save();

                    ProcessWalletWithdrawals::dispatch($incomeWithdrawalRequest);
                }
            }

            return redirect()->route('admin.income-withdrawal-requests.index')->with(['success' => 'Retry transfer fund successfully']);
        }

        return redirect()->route('admin.income-withdrawal-requests.index')->with(['success' => 'Please select atleast one record.']);
    }
}
