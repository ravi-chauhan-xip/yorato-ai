<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MemberLevelDetailListBuilder extends ListBuilder
{
    public static string $name = 'Level Detail';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        if ($extras['level']) {
            $query = Member::with('user', 'sponsor', 'deposits')
                ->where('sponsor_path', 'like', "{$extras['path']}/%")
                ->where('level', $extras['memberLevel'] + $extras['level'])
                ->orderByDesc('id');
        } else {
            $query = Member::with('user', 'sponsor', 'deposits')
                ->where('sponsor_path', 'like', "{$extras['path']}/%")
                ->orderByDesc('id');
        }

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Joining Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            new ListBuilderColumn(
                name: 'Activation Date',
                property: 'activated_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            new ListBuilderColumn(
                name: 'User Wallet Address',
                property: 'user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->user->wallet_address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'Sponsor Wallet Address',
                property: 'sponsor.user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->sponsor->user->wallet_address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.reports.datatable.direct.status',
                options: Member::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
        ];
    }
}
