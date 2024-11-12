<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\BuyCoin;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BuyCoinListBuilder extends ListBuilder
{
    public static string $name = 'Swap';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = BuyCoin::with('member.user');

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
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            //            new ListBuilderColumn(
            //                name: 'Member ID',
            //                property: 'member.code',
            //                filterType: ListBuilderColumn::TYPE_TEXT,
            //                canCopy: true,
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
                name: 'Member Name',
                property: 'member.user.name',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Amount ('.env('APP_CURRENCY_USDT').')',
                property: 'dollar_amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->dollar_amount > 0 ? $model->dollar_amount : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Coin Price ('.env('APP_CURRENCY_USDT').')',
                property: 'coin_price',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->coin_price > 0 ? $model->coin_price : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Amount ('.env('APP_CURRENCY').')',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->amount > 0 ? $model->amount : '0';
                },
            ),
        ];
    }
}
