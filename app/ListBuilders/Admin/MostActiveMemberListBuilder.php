<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MostActiveMemberListBuilder extends ListBuilder
{
    public static string $name = 'Most Active User';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Member::whereHas('loginLogs')
            ->withCount('loginLogs')
            ->orderByDesc('login_logs_count');

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
                property: 'user.wallet_address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->user->wallet_address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'Last Login',
                property: 'lastLoginLog.created_at',
                filterType: ListBuilderColumn::TYPE_NONE,
                exportCallback: function ($model) {
                    return $model->lastLoginLog->created_at->dateTimeFormat();
                },
                viewCallback: function ($model) {
                    return $model->lastLoginLog->created_at->dateTimeFormat();
                }
            ),
            new ListBuilderColumn(
                name: 'Total Login',
                property: 'login_logs_count',
                filterType: ListBuilderColumn::TYPE_NONE,
            ),
        ];
    }
}
