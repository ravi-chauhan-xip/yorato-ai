<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

/**
 * Class MemberPresenter
 */
class MemberStatusLogPresenter extends Presenter
{
    /**
     * @return string
     */
    public function lastStatus()
    {
        if ($this->entity->isLastFree()) {
            return 'Free';
        } elseif ($this->entity->isLastBlocked()) {
            return 'Blocked';
        } else {
            return 'Active';
        }
    }

    /**
     * @return string
     */
    public function newStatus()
    {
        if ($this->entity->isNewFree()) {
            return 'Free';
        } elseif ($this->entity->isNewBlocked()) {
            return 'Blocked';
        } else {
            return 'Active';
        }
    }
}
