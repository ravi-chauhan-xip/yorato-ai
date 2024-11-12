<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\IncomeWithdrawalRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IncomeWithdrawalRequestListBuilder extends ListBuilder
{
    public static string $name = 'Withdrawal Requests';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = IncomeWithdrawalRequest::with('member.user');

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function beforeDataTable(array $extras = [], ?Builder $query = null): string
    {
        return view('admin.income-withdrawal.status-change');
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Select',
                property: '#',
                view: 'admin.income-withdrawal.datatable.checkbox',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            new ListBuilderColumn(
                name: 'Action',
                property: 'action',
                view: 'admin.income-withdrawal.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.income-withdrawal.datatable.status',
                options: IncomeWithdrawalRequest::STATUSES,
                exportCallback: function ($model) {
                    return IncomeWithdrawalRequest::STATUSES[$model->status];
                }
            ),
            new ListBuilderColumn(
                name: 'Blockchain Status',
                property: 'blockchain_status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.income-withdrawal.datatable.blockchain-status',
                options: IncomeWithdrawalRequest::BLOCKCHAIN_STATUSES,
                exportCallback: function ($model) {
                    return IncomeWithdrawalRequest::BLOCKCHAIN_STATUSES[$model->blockchain_status];
                }
            ),
            new ListBuilderColumn(
                name: 'User Wallet Address',
                property: 'member.user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->member->user->wallet_address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'From Address',
                property: 'from_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    if ($model->from_address) {
                        return view('admin.web3-address', [
                            'address' => $model->from_address,
                        ]);
                    } else {
                        return 'N/A';
                    }
                }
            ),
            new ListBuilderColumn(
                name: 'To Address',
                property: 'to_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    if ($model->to_address) {
                        return view('admin.web3-address', [
                            'address' => $model->to_address,
                        ]);
                    } else {
                        return 'N/A';
                    }
                }
            ),
            new ListBuilderColumn(
                name: 'Amount',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
                exportCallback: function ($model) {
                    return $model->amount > 0 ? toHumanReadable($model->amount) : '0';
                },
            ),
            //            new ListBuilderColumn(
            //                name: 'Coin Price',
            //                property: 'coin_price',
            //                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            //                isCurrency: true,
            //                exportCallback: function ($model) {
            //                    return $model->coin_price > 0 ? toHumanReadable($model->coin_price) : '0';
            //                },
            //            ),
            //            new ListBuilderColumn(
            //                name: 'Amount ('.env('APP_CURRENCY').')',
            //                property: 'coin',
            //                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            //                exportCallback: function ($model) {
            //                    return $model->coin > 0 ? toHumanReadable($model->coin) : '0';
            //                },
            //            ),
            new ListBuilderColumn(
                name: 'Service Charge ('.env('APP_CURRENCY').')',
                property: 'service_charge',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->service_charge > 0 ? toHumanReadable($model->service_charge) : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Total ('.env('APP_CURRENCY').')',
                property: 'total',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->total > 0 ? toHumanReadable($model->total) : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Transaction Hash',
                property: 'tx_hash',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'admin.income-withdrawal.datatable.tx-hash',
            ),
            new ListBuilderColumn(
                name: 'Error',
                property: 'error',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
        ];
    }
}
