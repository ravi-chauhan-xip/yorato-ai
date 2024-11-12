<?php

namespace App\Presenters;

use App\Models\KYC;
use App\Models\Member;
use App\Models\User;
use Laracasts\Presenter\Presenter;

/**
 * Class MemberPresenter
 */
class MemberPresenter extends Presenter
{
    /**
     * @return string
     */
    public function genealogyImage()
    {
        if (! $image = $this->entity->getFirstMediaUrl(Member::MC_PROFILE_IMAGE)) {
            if ($this->entity->user->gender == User::GENDER_FEMALE) {
                $image = asset('images/blank-female.svg');
            } else {
                $image = asset('images/blank.svg');
            }
        }

        return $image;
    }

    public function genealogyImageBackground(): string
    {
        if ($this->entity->status == Member::STATUS_FREE_MEMBER) {
            return '#f50114';
        } elseif ($this->entity->status == Member::STATUS_ACTIVE) {
            return '#38ba4b';
        } elseif ($this->entity->status == Member::STATUS_BLOCKED) {
            return '#1f1b20';
        }

        return '';
    }

    /**
     * @return string
     */
    public function profileImage()
    {
        if (! $image = $this->entity->getFirstMediaUrl(Member::MC_PROFILE_IMAGE)) {
            if ($this->entity->user->gender == User::GENDER_FEMALE) {
                $image = asset('images/female.png');
            } else {
                $image = asset('images/user.png');
            }
        }

        return $image;
    }

    /**
     * @return string
     */
    public function referralLink()
    {
        return route('user.register.create', ['referralWalletAddress' => $this->entity->user->wallet_address]);
    }

    public function referralLinkLeft(): string
    {
        return route('user.register.create', ['referralWalletAddress' => $this->entity->user->wallet_address, 'side' => Member::PARENT_SIDE_LEFT]);
    }

    public function referralLinkRight(): string
    {
        return route('user.register.create', ['referralWalletAddress' => $this->entity->user->wallet_address, 'side' => Member::PARENT_SIDE_RIGHT]);
    }

    public function kycStatus(): string
    {
        if ($this->entity->kyc) {
            return KYC::STATUSES[$this->entity->kyc->status];
        } else {
            return 'N/A';
        }
    }

    /**
     * @return string
     */
    public function status()
    {
        if ($this->entity->isFree()) {
            return 'Free';
        } elseif ($this->entity->isBlocked()) {
            return 'Blocked';
        } else {
            return 'Active';
        }
    }

    public function isPaid()
    {
        if ($this->entity->is_paid == Member::IS_UN_PAID) {
            return 'Un Paid';
        } else {
            return 'Paid';
        }
    }

    public function walletBalance(): string
    {
        return env('APP_CURRENCY').round($this->entity->wallet_balance, 2);
    }
}
