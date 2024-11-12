<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminListBuilder extends ListBuilder
{
    public static string $name = 'Admins';

    public static string $permissionPrefix = 'Admins';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        return self::buildQuery(
            Admin::with('creatorId')->whereNotIn('id', [1]),
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('admin.admins.create');
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Action',
                property: 'action',
                view: 'admin.admins.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            new ListBuilderColumn(
                name: 'Creator Name',
                property: 'creatorId.name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Name',
                property: 'name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Email',
                property: 'email',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Mobile',
                property: 'mobile',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Is Super Admin',
                property: 'is_super',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.admins.datatable.is-super',
                options: ['true' => 'Yes', 'false' => 'No'],
                exportCallback: function ($model) {
                    return $model->present()->isSuper();
                }
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.admins.datatable.status',
                options: Admin::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
        ];
    }
}
