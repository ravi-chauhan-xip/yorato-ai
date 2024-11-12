<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Package;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PackageListBuilder extends ListBuilder
{
    public static string $name = 'Packages';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Package::orderBy('id', 'asc');

        return self::buildQuery(
            $query,
            $request
        );
    }

    //    public static function createUrl(): string|null
    //    {
    //        return route('admin.packages.create');
    //    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Action',
                property: 'action',
                view: 'admin.package.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.package.datatable.status',
                options: Package::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE
            ),
            new ListBuilderColumn(
                name: 'Name',
                property: 'name',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Amount',
                property: 'amount',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                isCurrency: true,
            ),
            new ListBuilderColumn(
                name: 'Min Staking',
                property: 'staking_min',
                isCurrency: true,
                viewCallback: function ($model) {
                    return $model->staking_min > 0 ? toHumanReadable($model->staking_min) : 'N/A';
                },
                exportCallback: function ($model) {
                    return $model->staking_min > 0 ? toHumanReadable($model->staking_min) : 'N/A';
                }
            ),
            new ListBuilderColumn(
                name: 'Max Staking',
                property: 'staking_max',
                isCurrency: true,
                viewCallback: function ($model) {
                    return $model->staking_max > 0 ? toHumanReadable($model->staking_max) : 'N/A';
                },
                exportCallback: function ($model) {
                    return $model->staking_max > 0 ? toHumanReadable($model->staking_max) : 'N/A';
                }
            ),
        ];
    }
}
