<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\BinaryMatch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TeamMatchingWalletIncomeListBuilder extends ListBuilder
{
    public static string $name = 'Team Matching Wallet Income';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = BinaryMatch::with('member.user');

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
