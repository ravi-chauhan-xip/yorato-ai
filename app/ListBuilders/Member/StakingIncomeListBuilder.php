<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\StakingIncome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StakingIncomeListBuilder extends ListBuilder
{
    public static string $name = 'Staking Income';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = StakingIncome::with('member.user')->where('member_id', $extras['member_id']);

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
                name: 'Stake Amount',
                property: 'stack_amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true
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
