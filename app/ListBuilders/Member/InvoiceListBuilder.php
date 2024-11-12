<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\TopUp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InvoiceListBuilder extends ListBuilder
{
    public static string $name = 'Invoices';

    public static array $breadCrumbs = [
        'Invoices',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = TopUp::where('member_id', $extras['member_id']);

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
                name: 'Invoice No',
                property: 'invoice_no',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Action',
                property: 'invoice',
                view: 'member.invoice.datatable.view-invoice',
                shouldExport: false,
            ),
        ];
    }
}
