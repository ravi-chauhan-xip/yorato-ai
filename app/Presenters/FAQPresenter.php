<?php

namespace App\Presenters;

use App\Models\Faq;
use Laracasts\Presenter\Presenter;

/**
 * Class MemberPresenter
 */
class FAQPresenter extends Presenter
{
    /**
     * @return string
     */
    public function status()
    {
        return Faq::STATUSES[$this->entity->status];

    }
}
