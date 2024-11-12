<?php

namespace App\ListBuilders\Admin;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\CompanyWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CompanyWalletListBuilder extends ListBuilder
{
    public static string $name = 'Company Wallet';

    public static string $permissionPrefix = 'Company Wallet';

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        $query = CompanyWallet::query();

        return self::buildQuery(
            $query,
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('admin.company-wallet.create');
    }

    public static function columns(): array
    {
        return [
            new ListBuilderColumn(
                name: 'Action',
                property: 'action',
                view: 'admin.company-wallet.datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Date',
                property: 'created_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'admin.company-wallet.datatable.status',
                options: CompanyWallet::STATUSES,
                exportCallback: function ($model) {
                    return CompanyWallet::STATUSES[$model->status] ?? 'N/A';
                }
            ),
            new ListBuilderColumn(
                name: 'Name',
                property: 'name',
                filterType: ListBuilderColumn::TYPE_TEXT,
            ),
            new ListBuilderColumn(
                name: 'Wallet Address',
                property: 'address',
                filterType: ListBuilderColumn::TYPE_TEXT,
                viewCallback: function ($model) {
                    return view('admin.web3-address', [
                        'address' => $model->address,
                    ]);
                }
            ),
            new ListBuilderColumn(
                name: 'BNB Balance',
                property: 'bnb_balance',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->bnb_balance > 0 ? toHumanReadable($model->bnb_balance) : '0';
                },
                viewCallback: function ($model) {
                    return $model->bnb_balance > 0 ? toHumanReadable($model->bnb_balance) : '0';
                }
            ),
            new ListBuilderColumn(
                name: 'USDT Balance',
                property: 'usdt_balance',
                filterType: ListBuilderColumn::TYPE_NUMBER_RANGE,
                exportCallback: function ($model) {
                    return $model->usdt_balance > 0 ? toHumanReadable($model->usdt_balance) : '0';
                },
                viewCallback: function ($model) {
                    return $model->usdt_balance > 0 ? toHumanReadable($model->usdt_balance) : '0';
                }
            ),
            new ListBuilderColumn(
                name: 'Locked At',
                property: 'locked_at',
                filterType: ListBuilderColumn::TYPE_DATE_RANGE,
            ),
        ];
    }
}
