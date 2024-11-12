<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\Bank;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BankListBuilder extends ListBuilder
{
    public static string $name = 'Banking Partner';

    public static array $breadCrumbs = [
        'Banking Partner',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = Bank::with('media')->whereStatus(Bank::STATUS_ACTIVE);

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
                name: 'Bank Name',
                property: 'name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Bank Branch',
                property: 'branch_name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Account Type',
                property: 'ac_type',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.banking.datatable.ac_type',
                options: Bank::TYPES,
                exportCallback: function ($model) {
                    return $model->present()->acType();
                }
            ),
            new ListBuilderColumn(
                name: 'Account Holder Name',
                property: 'account_holder_name',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Account Number',
                property: 'ac_number',
                filterType: ListBuilderColumn::TYPE_TEXT,
                exportCallback: function ($model) {
                    return "{$model->ac_number} ";
                }
            ),
            new ListBuilderColumn(
                name: 'IFSC Code',
                property: 'ifsc',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'UPI ID',
                property: 'upi_id',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'QR Code',
                property: 'qr_code',
                view: 'member.banking.datatable.qr-image',
                shouldExport: false,
            ),
        ];
    }
}
