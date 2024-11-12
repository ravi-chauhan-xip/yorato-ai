<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\UserDeposit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DepositListBuilder extends ListBuilder
{
    public static string $name = 'Deposit';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        return self::buildQuery(
            UserDeposit::whereMemberId($extras['member_id']),
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('user.deposits.create');
    }

    public static function createButtonName(): ?string
    {
        return 'Deposit';
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
                name: 'Amount ('.env('APP_CURRENCY').')',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->amount > 0 ? $model->amount : '0';
                },
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
                name: 'Transaction Hash',
                property: 'transaction_hash',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'member.deposits.datatable.tx-hash',
            ),
        ];
    }
}
