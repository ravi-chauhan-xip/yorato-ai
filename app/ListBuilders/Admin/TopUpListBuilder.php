<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Package;
use App\Models\TopUp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TopUpListBuilder extends ListBuilder
{
    public static string $name = 'Top Up';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = TopUp::with('member.user', 'fromMember.user', 'package', 'toppedUp.user', 'userDeposit');

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createButtonName(): ?string
    {
        return 'Topup';
    }

    public static function createUrl(): ?string
    {
        return route('admin.top-up.create');
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
                name: 'Package',
                property: 'package.name',
                dbColumn: 'package_id',
                filterType: ListBuilderColumn::TYPE_SELECT,
                options: Package::all()->mapWithKeys(function (Package $package) {
                    return [$package->id => $package->name];
                })->toArray(),
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
                view: 'admin.topup.datatable.tx-hash',
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
                view: 'admin.topup.datatable.status',
                options: TopUp::STATUSES,
            ),
            new ListBuilderColumn(
                name: 'Top Up By',
                property: 'done_by',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.topup.datatable.done-by',
                options: TopUp::DONE_BYES,
            ),
        ];
    }
}
