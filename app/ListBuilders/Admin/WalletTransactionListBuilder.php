<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\WalletTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WalletTransactionListBuilder extends ListBuilder
{
    public static string $name = 'Wallet Transactions';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = WalletTransaction::with('member.user');

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            //            new ListBuilderColumn(
            //                name: 'Member ID',
            //                property: 'member.code',
            //                filterType: ListBuilderColumn::TYPE_TEXT
            //            ),

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
                name: 'Total Amount ('.env('APP_CURRENCY_USDT').')',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            ),
            //            new ListBuilderColumn(
            //                name: 'Admin Charge',
            //                property: 'admin_charge',
            //                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            //                isCurrency: true,
            //            ),
            //            new ListBuilderColumn(
            //                name: 'Net Amount',
            //                property: 'total',
            //                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            //                isCurrency: true,
            //            ),
            new ListBuilderColumn(
                name: 'Type',
                property: 'type',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.wallet-transactions.datatable.type',
                options: WalletTransaction::TYPES,
                exportCallback: function ($model) {
                    return $model->present()->type();
                }
            ),
            new ListBuilderColumn(
                name: 'Remark',
                property: 'comment',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'admin.wallet-transactions.datatable.remark',
            ),
        ];
    }
}
