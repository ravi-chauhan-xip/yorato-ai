<?php

namespace App\ListBuilders;

use App\Library\Exporter;
use DataTables;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

abstract class ListBuilder
{
    public static string $name;

    public Builder $queryBuilder;

    public static string $viewPrefix;

    public static array $breadCrumbs = [];

    public static string $defaultSort = '-id';

    abstract public static function query(array $extras = [], ?Request $request = null): Builder;

    /**
     * @return ListBuilderColumn[]
     */
    abstract public static function columns(): array;

    public static function createUrl(): ?string
    {
        return null;
    }

    public static function createButtonName(): ?string
    {
        return null;
    }

    public static function createUrl2(): ?string
    {
        return null;
    }

    public static function createButtonName2(): ?string
    {
        return null;
    }

    public static function beforeDataTable(array $extras = [], ?Builder $query = null): ?string
    {
        return null;
    }

    /**
     * @throws Exception
     */
    public static function render(array $extras = [], ?string $name = null): Renderable|JsonResponse|RedirectResponse
    {
        if ($name) {
            static::$name = $name;
        }

        if (request()->ajax() || request()->has('export')) {
            if (request()->ajax()) {
                $query = static::query($extras);

                $dataTable = DataTables::of($query);

                $rawColumns = [];

                foreach (static::columns() as $column) {
                    $column->processDataTableColumn($dataTable, $rawColumns);
                }

                return $dataTable
                    ->rawColumns($rawColumns)
                    ->addIndexColumn()
                    ->with('beforeDataTable', static::beforeDataTable($extras, $query->clone()))
                    ->toJson();
            }

            if (request()->has('export')) {
                if (static::query($extras, request())->count() == 0) {
                    return redirect()->back()->with(['error' => 'No records found to export']);
                }

                return Exporter::create(ListBuilderExport::class, static::class)
                    ->usingFilePrefix(static::$name)
                    ->withExtras($extras)
                    ->redirect();
            }
        }

        return view(self::$viewPrefix.'.list-builder.index', [
            'listBuilderClass' => static::class,
        ]);
    }

    public static function exportHeadings(): array
    {
        $headings = [];

        foreach (static::columns() as $column) {
            if ($column->shouldExport) {
                $headings[] = $column->title();
            }
        }

        return $headings;
    }

    public static function exportRow($model): array
    {
        $values = [];

        foreach (static::columns() as $column) {
            if ($column->shouldExport) {
                $values[] = $column->getValue($model, true);
            }
        }

        return $values;
    }

    protected static function buildQuery(Builder $query, ?Request $request = null): Builder
    {
        $eagerLoads = [];
        $allowedFilters = [];

        foreach (static::columns() as $column) {
            $column->processQueryBuilderColumn($eagerLoads, $allowedFilters);
        }

        return QueryBuilder::for($query, $request)
            ->with($eagerLoads)
            ->defaultSort(static::$defaultSort)
            ->allowedFilters($allowedFilters)
            ->getEloquentBuilder();
    }
}
