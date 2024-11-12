<?php

namespace App\ListBuilders\Member;

use App\ListBuilders\ListBuilder;
use App\ListBuilders\ListBuilderColumn;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SupportTicketListBuilder extends ListBuilder
{
    public static string $name = 'Support Ticket';

    public static array $breadCrumbs = [
        'Support Ticket',
    ];

    public static function query(array $extras = [], ?Request $request = null): Builder
    {
        return self::buildQuery(
            SupportTicket::whereMemberId($extras['member_id']),
            $request
        );
    }

    public static function createUrl(): ?string
    {
        return route('user.support.create');
    }

    public static function createButtonName(): ?string
    {
        return 'Generate';
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
                view: 'member.support..datatable.action',
                shouldExport: false,
            ),
            new ListBuilderColumn(
                name: 'Status',
                property: 'status',
                filterType: ListBuilderColumn::TYPE_SELECT,
                view: 'member.support..datatable.status',
                options: SupportTicket::STATUSES,
                exportCallback: function ($model) {
                    return $model->present()->status();
                }
            ),
            new ListBuilderColumn(
                name: 'Ticket ID',
                property: 'ticket_id',
                filterType: ListBuilderColumn::TYPE_TEXT
            ),
            new ListBuilderColumn(
                name: 'Subject',
                property: 'title',
                filterType: ListBuilderColumn::TYPE_TEXT,
                view: 'member.support..datatable.subject',
            ),
        ];
    }
}
