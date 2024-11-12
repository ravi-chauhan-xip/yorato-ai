<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\BuyCoin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BuyCoinListBuilder extends ListBuilder
{
    public static string $name = 'Buy Coins';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = BuyCoin::with('member.user')
            ->where('member_id', $extras['member_id']);

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('user.buy-coins.create');
    }

    public static function createButtonName(): ?string
    {
        return 'SWAP';
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
