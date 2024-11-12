<?php

namespace App\FilterBuilders\Admin;

use App\FilterBuilders\FilterBuilder;
use App\FilterBuilders\FilterBuilderInterface;
use App\Models\IncomeWithdrawalRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class WithdrawalRequestFilterBuilder extends FilterBuilder implements FilterBuilderInterface
{
    public static function query(?array $extras = [], ?Request $request = null): Builder
    {
        return QueryBuilder::for(IncomeWithdrawalRequest::class, $request)
            ->defaultSort('-id')
            ->allowedFilters([
                'created_at', 'invoice_no', 'member.code', 'member.user.name', 'member.user.wallet_address', 'tx_hash', 'error', 'from_address', 'to_address',
                AllowedFilter::exact('status'),
                AllowedFilter::exact('blockchain_status'),
                AllowedFilter::scope('min_amount'),
                AllowedFilter::scope('max_amount'),
                AllowedFilter::scope('min_coin_price'),
                AllowedFilter::scope('max_coin_price'),
                AllowedFilter::scope('min_coin'),
                AllowedFilter::scope('max_coin'),
                AllowedFilter::scope('min_service_charge'),
                AllowedFilter::scope('max_service_charge'),
                AllowedFilter::scope('min_total'),
                AllowedFilter::scope('max_total'),
                AllowedFilter::scope('createdAtFrom'),
                AllowedFilter::scope('createdAtTo'),
            ])
            ->with(['member.user'])
            ->getEloquentBuilder();
    }
}
