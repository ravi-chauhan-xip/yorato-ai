<?php

namespace App\Exports\Admin;

use App\Models\IncomeWithdrawalRequest;
use App\Traits\FilterExportable;
use Laracasts\Presenter\Exceptions\PresenterException;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IncomeWithdrawalRequestExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping
{
    use FilterExportable;

    /**
     * @param  IncomeWithdrawalRequest  $incomeWithdrawalRequest
     *
     * @throws PresenterException
     */
    public function map($incomeWithdrawalRequest): array
    {
        return [
            $incomeWithdrawalRequest->created_at->dateTimeFormat(),
            IncomeWithdrawalRequest::STATUSES[$incomeWithdrawalRequest->status],
            IncomeWithdrawalRequest::BLOCKCHAIN_STATUSES[$incomeWithdrawalRequest->blockchain_status],
            $incomeWithdrawalRequest->member->user->wallet_address,
            $incomeWithdrawalRequest->member ? $incomeWithdrawalRequest->member->user->name : '--',
            $incomeWithdrawalRequest->from_address,
            $incomeWithdrawalRequest->to_address,
            $incomeWithdrawalRequest->amount,
            $incomeWithdrawalRequest->coin_price,
            $incomeWithdrawalRequest->coin,
            $incomeWithdrawalRequest->service_charge,
            $incomeWithdrawalRequest->total,
            $incomeWithdrawalRequest->tx_hash,
            $incomeWithdrawalRequest->error,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function headings(): array
    {
        return [
            'Date',
            'Status',
            'Blockchain Status',
            'User Wallet Address',
            'Member Name',
            'From Address',
            'To Address',
            'Amount',
            'Coin Price',
            'Amount',
            'Service Charge',
            'Total',
            'Transaction Hash',
            'Error',
        ];
    }
}
