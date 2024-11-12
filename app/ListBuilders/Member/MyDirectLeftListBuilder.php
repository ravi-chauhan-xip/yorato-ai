<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MyDirectLeftListBuilder extends ListBuilder
{
    public static string $name = 'My Left Direct';

    public static array $breadCrumbs = [
        'My Left Direct',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Member::where('path', 'like', "{$extras['path']}%")
            ->where('sponsor_id', $extras['member_id']);

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
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            new ListBuilderColumn(
                name: 'Member ID',
                property: 'code',
                filterType: ListBuilderColumn::TYPE_TEXT,
                canCopy: true,
            ),
            new ListBuilderColumn(
                name: 'Member Name',
                property: 'user.name',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Parent ID',
                property: 'parent.code',
                filterType: ListBuilderColumn::TYPE_TEXT,
                canCopy: true,
            ),
            new ListBuilderColumn(
                name: 'Left',
                property: 'left_bv',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
            ),
            new ListBuilderColumn(
                name: 'Right',
                property: 'right_bv',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
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
