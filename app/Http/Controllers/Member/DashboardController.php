<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\IncomeWithdrawalRequest;
use App\Models\Member;
use App\Models\StakeCoin;
use App\Models\UserDeposit;
use Carbon\Carbon;
use Jorenvh\Share\Share;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = Member::where('sponsor_id', $this->member->id)->count();

        $profileImageUrl = $this->member->getFirstMediaUrl(Member::MC_PROFILE_IMAGE);

        $rewardDetails = [];
        $member = Member::whereHas('user', function ($q) {
            return $q->where('wallet_address', $this->member->user->wallet_address);
        })->first();

        $todayTotalEarning = $this->member->walletTransactions()->credit()
            ->whereNotIn('responsible_type', [IncomeWithdrawalRequest::class, UserDeposit::class, Admin::class])
            ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
            ->sum('amount');

        return view('member.dashboard.index', [
            'rewardDetails' => $rewardDetails,
            'totalStake' => toHumanReadable(StakeCoin::where('member_id', $this->member->id)->sum('amount')),
            'totalEarning' => toHumanReadable($this->member->walletTransactions()->credit()
                ->whereNotIn('responsible_type', [IncomeWithdrawalRequest::class, UserDeposit::class])
                ->sum('amount')),
            'todayTotalEarning' => toHumanReadable($todayTotalEarning),
            'todayWithdrawals' => toHumanReadable(IncomeWithdrawalRequest::whereStatus(IncomeWithdrawalRequest::STATUS_COMPLETED)
                ->where('member_id', $this->member->id)
                ->where('blockchain_status', IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_SUCCESS)
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->sum('amount')),
            'totalSales' => $totalSales,
            'profileImageUrl' => $profileImageUrl,
            'myDirects' => $this->member->sponsored()->count(),
            'myDownLine' => Member::where('path', 'like', "{$this->member->path}/%")->whereNotNull('activated_at')->count(),
            'socialMediaLinks' => (new Share)->page($this->member->present()->referralLink(), null, ['class' => 'social-link', 'title' => 'Referral Link'])
                ->facebook()
                ->whatsapp(),
            'socialMediaLinksLeft' => (new Share)->page($this->member->present()->referralLinkLeft(), null, ['class' => 'social-link', 'title' => 'Left Referral Link'])
                ->facebook()
                ->whatsapp(),
            'socialMediaLinksRight' => (new Share)->page($this->member->present()->referralLinkRight(), null, ['class' => 'social-link', 'title' => 'Right Referral Link'])
                ->facebook()
                ->whatsapp(),
            'member' => $this->member,
        ]);
    }
}
