<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\News;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NewsListBuilder extends ListBuilder
{
    public static string $name = 'News Feed';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        return self::buildQuery(
            News::query(),
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('admin.news.create');
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
                name: 'Action',
                property: 'action',
                view: 'admin.news.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.news.datatable.status',
                options: News::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
            new ListBuilderColumn(
                name: 'Title',
                property: 'title',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'admin.news.datatable.title',
            ),
            new ListBuilderColumn(
                name: 'Description',
                property: 'description',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'admin.news.datatable.description',
            ),
        ];
    }
}
