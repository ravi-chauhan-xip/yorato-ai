<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\StakingBinaryMatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TeamMatchingStakingIncomeListBuilder extends ListBuilder
{
    public static string $name = 'Team Matching Staking Income';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = StakingBinaryMatch::with('member.user')->where('member_id', $extras['member_id']);

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
