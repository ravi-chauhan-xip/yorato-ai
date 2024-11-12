<?php

namespace App\ListBuilders;

use App\Models\IncomeWithdrawalRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IncomeWithdrawalRequestListBuilder extends ListBuilder
{
    public static string $name = 'Withdrawal Requests';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {

        $query = IncomeWithdrawalRequest::with('member')->where('member_id', $extras['member_id']);

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('user.income-withdrawals.create');
    }

    public static function createButtonName(): ?string
    {
        return 'Withdrawal';
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.income-withdrawal.datatable.status',
                options: IncomeWithdrawalRequest::STATUSES,
                exportCallback: function ($model) {
                    return IncomeWithdrawalRequest::STATUSES[$model->status];
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
                view: 'member.income-withdrawal.datatable.tx-hash',
            ),
        ];
    }
}
