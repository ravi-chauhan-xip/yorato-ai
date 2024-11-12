<?php

namespace App\Presenters;

use App\Models\SupportTicket;
use Laracasts\Presenter\Presenter;

/**
 * Class SupportTicketPresenter
 */
class SupportTicketPresenter extends Presenter
{
    public function status(): string
    {
        return SupportTicket::STATUSES[$this->entity->status];
    }
}
