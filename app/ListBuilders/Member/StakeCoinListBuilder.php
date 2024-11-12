<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\StakeCoin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StakeCoinListBuilder extends ListBuilder
{
    public static string $name = 'Stake';

    public static array $breadCrumbs = [
        'My Stake',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = StakeCoin::where('member_id', $extras['member_id']);

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function beforeDataTable(array $extras = [], ?Builder $query = null): string
    {
        return view('member.stake.aggregates', [
            'totalStake' => toHumanReadable($query->sum('amount')).' ('.env('APP_CURRENCY').')',
        ]);
    }

    public static function createButtonName(): ?string
    {
        return 'Stake By Web3';
    }

    public static function createUrl(): ?string
    {
        return route('user.stake.create');
    }

    public static function createButtonName2(): ?string
    {
        return 'Stake By Wallet';
    }

    public static function createUrl2(): ?string
    {
        return route('user.stake.wallet-create');
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
                name: 'Amount ('.env('APP_CURRENCY').')',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->amount > 0 ? $model->amount : '0';
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
                name: 'Capping Days',
                property: 'capping_days',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,

            ),
            new ListBuilderColumn(
                name: 'Remaining Capping Days',
                property: 'remaining_days',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,

            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.stake.datatable.status',
                options: StakeCoin::STATUSES,
            ),
            new ListBuilderColumn(
                name: 'Stake By',
                property: 'done_by',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.stake.datatable.done-by',
                options: StakeCoin::DONE_BYES,
            ),
        ];
    }
}
