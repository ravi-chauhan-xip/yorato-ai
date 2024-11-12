<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\MemberStat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TopEarnersListBuilder extends ListBuilder
{
    public static string $name = 'Top Earners';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = MemberStat::with('member')->where('all_income', '>', 0)->orderBy('all_income', 'desc');

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function columns(): array
    {
        return [
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
                name: 'Direct Wallet Income',
                property: 'direct_income',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Team Matching Wallet Income',
                property: 'team_matching',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Staking Income',
                property: 'staking_income',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Direct Sponsor Staking Income',
                property: 'direct_sponsor_staking',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Team Matching Staking Income',
                property: 'team_matching_staking',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Leadership Bonus',
                property: 'leadership_bonus',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Admin Credit',
                property: 'admin_credit',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'All Income',
                property: 'all_income',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
        ];
    }
}
