<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MemberListBuilder extends ListBuilder
{
    public static string $name = 'Users';

    public static string $defaultSort = 'id';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        return self::buildQuery(
            Member::query(),
            $request
        );
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Action',
                property: 'action',
                view: 'admin.members.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Joining Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            new ListBuilderColumn(
                name: 'Activation Date',
                property: 'activated_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            new ListBuilderColumn(
                name: 'User Wallet Address',
                property: 'user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function (Member $model) {
                    return view('admin.web3-address', [
                        'address' => $model->user->wallet_address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'Balance('.env('APP_CURRENCY_USDT').')',
                property: 'wallet_balance',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            ),

            new ListBuilderColumn(
                name: 'Sponsor Wallet Address',
                property: 'sponsor.user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function (Member $model) {
                    return view('admin.web3-address', [
                        'address' => $model->sponsor ? $model->sponsor->user->wallet_address : '',
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'User Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.members.datatable.status',
                options: Member::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
        ];
    }
}
