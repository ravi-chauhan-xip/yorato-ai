<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\AdminBVLog;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminBVListBuilder extends ListBuilder
{
    public static string $name = 'Admin Power Log';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = AdminBVLog::with('member.user');

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
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
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
                name: 'POWER',
                property: 'bv',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->bv > 0 ? $model->bv : '0';
                },
            ),
            new ListBuilderColumn(
                name: 'Side',
                property: 'parent_side',
                filterType: ListBuilderColumn::TYPE_SELECT,
                options: Member::PARENT_SIDES,
                viewCallback: function ($model) {
                    return $model->parent_side == Member::PARENT_SIDE_LEFT ? 'Left' : 'Right';
                }, exportCallback: function ($model) {
                    return $model->parent_side == Member::PARENT_SIDE_LEFT ? 'Left' : 'Right';
                },
            ),

            new ListBuilderColumn(
                name: 'Type',
                property: 'type',
                filterType: ListBuilderColumn::TYPE_SELECT,
                options: AdminBVLog::TYPES,
                viewCallback: function ($model) {
                    return $model->type == AdminBVLog::TYPE_STAKE ? 'Stake' : 'TopUp';
                }, exportCallback: function ($model) {
                    return $model->type == AdminBVLog::TYPE_STAKE ? 'Stake' : 'TopUp';
                },
            ),

        ];
    }
}
