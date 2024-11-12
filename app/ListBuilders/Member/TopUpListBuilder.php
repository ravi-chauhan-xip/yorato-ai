<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Package;
use App\Models\TopUp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TopUpListBuilder extends ListBuilder
{
    public static string $name = 'Topup';

    public static array $breadCrumbs = [
        'My Topup',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = TopUp::where('member_id', $extras['member_id']);

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createButtonName(): ?string
    {
        return 'Topup By Web3';
    }

    public static function createUrl(): ?string
    {
        return route('user.top-up.create');
    }

    public static function createUrl2(): ?string
    {
        return route('user.top-up.wallet-create');
    }

    public static function createButtonName2(): ?string
    {
        return 'Topup By Wallet';
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
                name: 'Package',
                property: 'package.name',
                dbColumn: 'package_id',
                filterType: ListBuilderColumn::TYPE_SELECT,
                options: Package::all()->mapWithKeys(function (Package $package) {
                    return [$package->id => $package->name];
                })->toArray(),
                exportCallback: function ($model) {
                    return $model->package?->present()->nameAndAmount();
                },
                viewCallback: function ($model) {
                    return $model->package?->present()->nameAndAmount();
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
                name: 'Top Up From Address',
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
                view: 'member.top-up.datatable.status',
                options: TopUp::STATUSES,
            ),
            new ListBuilderColumn(
                name: 'Top Up By',
                property: 'done_by',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.top-up.datatable.done-by',
                options: TopUp::DONE_BYES,
            ),
        ];
    }
}
