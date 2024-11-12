<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\StakeCoin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StakeCoinListBuilder extends ListBuilder
{
    public static string $name = 'Stake Coin';

    public static array $breadCrumbs = [
        'Stake Coin',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = StakeCoin::with('userDeposit', 'fromMember.user');

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createButtonName(): ?string
    {
        return 'Stake';
    }

    public static function createUrl(): ?string
    {
        return route('admin.stake.create');
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
                name: 'Amount ('.env('APP_CURRENCY').')',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->amount > 0 ? $model->amount : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'From Address',
                property: 'userDeposit.from_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    if ($model->userDeposit && $model->userDeposit->from_address) {
                        return view('admin.web3-address', [
                            'address' => $model->userDeposit->from_address,
                        ]);
                    } else {
                        return 'N/A';
                    }
                }
            ),
            new ListBuilderColumn(
                name: 'To Address',
                property: 'userDeposit.to_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    if ($model->userDeposit && $model->userDeposit->to_address) {
                        return view('admin.web3-address', [
                            'address' => $model->userDeposit->to_address,
                        ]);
                    } else {
                        return 'N/A';
                    }
                }
            ),
            new ListBuilderColumn(
                name: 'Transaction Hash',
                property: 'userDeposit.transaction_hash',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'admin.stake.datatable.tx-hash',
            ),

            new ListBuilderColumn(
                name: 'Capping Days',
                property: 'capping_days',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->capping_days > 0 ? $model->capping_days : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Remaining Capping Days',
                property: 'remaining_days',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->remaining_days > 0 ? $model->remaining_days : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Stake From Address',
                property: 'fromMember.user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    if ($model->fromMember) {
                        return view('admin.web3-address', [
                            'address' => $model->fromMember->user->wallet_address,
                        ]);
                    } else {
                        return 'N/A';
                    }
                }
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.stake.datatable.status',
                options: StakeCoin::STATUSES,
            ),
            new ListBuilderColumn(
                name: 'Stake By',
                property: 'done_by',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.stake.datatable.done-by',
                options: StakeCoin::DONE_BYES,
            ),
        ];
    }
}
