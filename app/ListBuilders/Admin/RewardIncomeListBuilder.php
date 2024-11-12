<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\RewardIncome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RewardIncomeListBuilder extends ListBuilder
{
    public static string $name = 'Royalty reward income';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = RewardIncome::with('member.user', 'reward', 'walletTransactions');

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
            //            new ListBuilderColumn(
            //                name: 'Reward',
            //                property: 'reward.name',
            //                filterType: ListBuilderColumn::TYPE_TEXT
            //            ),
            new ListBuilderColumn(
                name: 'Amount',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true
            ),
            new ListBuilderColumn(
                name: 'Remark',
                property: 'walletTransactions.comment',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
        ];
    }
}
