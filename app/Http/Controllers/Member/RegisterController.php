<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\RegisterRequest;
use App\Jobs\AddMemberOnNetwork;
use App\Mail\SendGeneralMail;
use App\Models\Member;
use App\Models\Otp;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;
use Session;
use Throwable;

class RegisterController extends Controller
{
    public function create(): Renderable
    {
        return view('member.auth.register');
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        $sponsorUser = User::with('member')->whereWalletAddress($request->input('referralWalletAddress'))->first();

        if ($sponsorUser) {
            $sponsor = $sponsorUser->member;
        }

        if ($sponsor->isBlocked()) {
            return response()->json([
                'status' => false,
                'message' => 'Referral wallet address is blocked',
            ]);
        }

        try {
            DB::transaction(function () use ($sponsor, $request) {
                if ($request->get('side') == Member::PARENT_SIDE_LEFT) {
                    $parent = $sponsor->extremeLeftMember();
                } else {
                    $parent = $sponsor->extremeRightMember();
                }

                // If the sponsor does not have any children
                // Then sponsor does not have extremeLeftMember or extremeRightMember
                // In that case the sponsor is the parent
                if (! $parent) {
                    $parent = $sponsor;
                }

                $user = User::create([
                    'wallet_address' => $request->input('walletAddress'),
                ]);

                $user->assignRole('member');

                $member = Member::create([
                    'user_id' => $user->id,
                    'parent_id' => $parent->id,
                    'parent_side' => $request->get('side'),
                    'sponsor_id' => $sponsor->id,
                    'level' => $parent->level + 1,
                    'sponsor_level' => $sponsor->level + 1,
                    'status' => Member::STATUS_FREE_MEMBER,
                ]);

                Auth::login($user);

                if ($ip = $request->ip()) {
                    $user->member->loginLogs()->create([
                        'ip' => $ip,
                    ]);
                }

                AddMemberOnNetwork::dispatch($member);
            });
        } catch (Throwable $e) {
            return response()->json([
                'status' => false,
                'error' => 'Something wrong',
            ]);
        }

        Session::flash('success', 'User registered successfully');

        return response()->json([
            'status' => true,
            'error' => 'Member added successfully',
        ]);
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $user = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        if (! preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $request->input('email'))) {
            return response()->json(['status' => false, 'message' => 'Email ID format is invalid.']);
        }
        if (User::whereEmail($request->input('email'))->exists()) {
            return response()->json(['status' => false, 'message' => "We're sorry, but the email ID you provided already exists in our system."]);
        }
        if (env('APP_ENV') !== 'production') {
            $otp = '111111';
        } else {
            $otp = rand('100001', '999999');
        }

        Otp::create([
            'otp' => $otp,
            'email' => $request->input('email'),
            'status' => Otp::STATUS_UNUSED,
            'type' => Otp::ACTION_REGISTER,
        ]);
        if (settings('email_enabled')) {
            $title = 'Register OTP';
            $body = 'We have received a register request. Please register with your new OTP '.$otp;
            Mail::to($user['email'])->queue(new SendGeneralMail($user, $title, $body));
        }

        return response()->json(['status' => true, 'message' => 'OTP sent to you successfully.']);
    }

    public function getReferralWallet()
    {
        return response()->json(['status' => true, 'referralWalletAddress' => User::first()->wallet_address]);
    }
}
