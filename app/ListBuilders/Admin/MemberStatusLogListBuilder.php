<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\MemberStatusLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MemberStatusLogListBuilder extends ListBuilder
{
    public static string $name = 'Members Status Log';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = MemberStatusLog::where('member_id', $extras['member_id'])
            ->with(['user', 'member']);

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
                name: 'Admin Name',
                property: 'user.name',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Last Status',
                property: 'last_status',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'New Status',
                property: 'new_status',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
        ];
    }
}
