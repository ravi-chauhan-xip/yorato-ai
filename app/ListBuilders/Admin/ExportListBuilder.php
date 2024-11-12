<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExportListBuilder extends ListBuilder
{
    public static string $name = 'Exports (Deleted in 24 hours.)';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Export::where('admin_id', $extras['admin_id']);

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
                name: 'File Name',
                property: 'file_name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Link',
                property: 'link',
                view: 'admin.exports.datatable.link',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.exports.datatable.status',
                options: Export::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
        ];
    }
}
