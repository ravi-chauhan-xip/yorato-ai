<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\DirectSponsorStakingIncome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DirectSponsorStakeIncomeListBuilder extends ListBuilder
{
    public static string $name = 'Direct Sponsor Staking Income';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = DirectSponsorStakingIncome::with('member.user', 'fromMember.user');

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
                name: 'From User Wallet Address',
                property: 'fromMember.user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->fromMember->user->wallet_address,
                    ]);
                }
            ),
            //            new ListBuilderColumn(
            //                name: 'Percentage (%)',
            //                property: 'percentage',
            //                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            //            ),
            new ListBuilderColumn(
                name: 'Amount',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true
            ),
            new ListBuilderColumn(
                name: 'Remark',
                property: 'walletTransaction.comment',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
        ];
    }
}
