<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Library\Sms;
use App\Models\DirectSellerContract;
use App\Models\IncomeWithdrawalRequest;
use App\Models\Member;
use App\Models\StakeCoin;
use App\Models\SupportTicket;
use App\Models\TopUp;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class DashboardController extends Controller
{
    public function index()
    {
        $lastFiveRegisterMembers = Member::with('user', 'sponsor.user', 'media')->take(5)->latest('id')->get();

        $lastFiveActivation = Member::with('user', 'sponsor.user', 'media')
            ->whereNotNull('activated_at')
            ->take(5)
            ->latest('activated_at')
            ->get();

        $dayCountMembersJoins = collect();
        $dayWisePackageSubscriptions = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $totalMember = Member::select(DB::raw('count(*) as total_members'))
                ->whereDate('created_at', $date)->first();

            $amount = StakeCoin::whereDate('created_at', $date)
                ->sum('amount');

            $dayCountMembersJoins->push([
                'day' => $date->format('d-m-Y'),
                'total_member' => $totalMember->total_members,
            ]);
            $dayWisePackageSubscriptions->push([
                'day' => $date->format('d-m-Y'),
                'amount' => $amount,
            ]);
        }

        $totalStake = StakeCoin::sum('amount');
        $totalTurnOverUSDT = toHumanReadable(StakeCoin::where('done_by', '!=', StakeCoin::DONE_BY_ADMIN)->sum('amount') + TopUp::where('done_by', '!=', TopUp::DONE_BY_ADMIN)->sum('amount'));
        $totalPreviousMonthTurnOverUSDT = toHumanReadable(StakeCoin::where('done_by', '!=', StakeCoin::DONE_BY_ADMIN)->whereBetween('created_at', [today()->subMonth()->startOfDay(), today()->subMonth()->endOfMonth()])->sum('amount')
            + TopUp::where('done_by', '!=', TopUp::DONE_BY_ADMIN)->whereBetween('created_at', [today()->subMonth()->startOfDay(), today()->subMonth()->endOfMonth()])->sum('amount'));

        $totalCurrentMonthTurnOverUSDT = toHumanReadable(StakeCoin::where('done_by', '!=', StakeCoin::DONE_BY_ADMIN)->whereBetween('created_at', [today()->startOfDay(), today()->endOfMonth()])->sum('amount') +
            TopUp::where('done_by', '!=', TopUp::DONE_BY_ADMIN)->whereBetween('created_at', [today()->startOfDay(), today()->endOfMonth()])->sum('amount'));

        return view('admin.dashboard', [
            'totalMembers' => Member::count(),
            'paidMembers' => Member::where('is_paid', Member::IS_PAID)->count(),
            'activeMembers' => Member::where('status', Member::STATUS_ACTIVE)->count(),
            'blockedMembers' => Member::whereStatus(Member::STATUS_BLOCKED)->count(),
            'todayActivation' => Member::whereDate('activated_at', '=', Carbon::today())->count(),
            'lastFiveRegisterMembers' => $lastFiveRegisterMembers,
            'lastFiveActivation' => $lastFiveActivation,
            'totalTurnOverUSDT' => $totalTurnOverUSDT,
            'todayWithdrawals' => toHumanReadable(IncomeWithdrawalRequest::whereStatus(IncomeWithdrawalRequest::STATUS_COMPLETED)
                ->where('blockchain_status', IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_SUCCESS)
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->sum('amount')),
            'totalPreviousMonthTurnOverUSDT' => $totalPreviousMonthTurnOverUSDT,
            'totalCurrentMonthTurnOverUSDT' => $totalCurrentMonthTurnOverUSDT,
            'openSupportTickets' => SupportTicket::open()->count(),
            'dayCountMembersJoins' => $dayCountMembersJoins,
            'dayWisePackageSubscriptions' => $dayWisePackageSubscriptions,
            'smsBalance' => Sms::balance(),
            'totalStake' => toHumanReadable($totalStake),
        ]);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws ValidationException
     */
    public function directSellerContract(Request $request): RedirectResponse|Renderable
    {
        $direct_seller_pdf = DirectSellerContract::first();

        if ($request->isMethod('post')) {

            $rules['status'] = 'required';

            $this->validate($request, $rules);

            if (isset($direct_seller_pdf)) {
                $direct_seller_pdf->status = $request->status;
                $direct_seller_pdf->save();
            } else {

                $direct_seller_pdf = DirectSellerContract::create([
                    'status' => $request->status,
                ]);
            }

            if ($fileName = $request->get('direct_seller_contract')) {
                $filePath = 'tmp/'.Str::beforeLast($fileName, '.');

                $direct_seller_pdf->addMediaFromDisk($filePath)
                    ->usingFileName($fileName)
                    ->toMediaCollection(DirectSellerContract::MC_DIRECT_SELLER_CONTRACT);
            }

            return redirect()->route('admin.websetting.direct-seller-contract')
                ->with(['success' => 'Direct Seller Contract updated successfully']);
        }

        return view('admin.web-settings.direct-seller-contract', ['direct_seller_pdf' => $direct_seller_pdf]);
    }
}
