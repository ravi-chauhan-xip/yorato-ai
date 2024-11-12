<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Jobs\Member\SendForgotPasswordSMS;
use App\Mail\SendGeneralMail;
use App\Models\Member;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mail;

class ForgotPasswordController extends Controller
{
    public function create(): Factory|View
    {
        return view('member.auth.forget');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'member_code' => 'required|exists:members,code',
        ], [
            'member_code.required' => 'Member ID is required.',
            'member_code.exists' => 'Member ID is incorrect',
        ]);

        if ($member = Member::whereCode($request->get('member_code'))->first()) {
            if ($member->isBlocked()) {
                return redirect()->back()->with('error', 'Member ID is blocked')->withInput();
            }
            $password = mt_rand(11111111, 99999999);

            $member->user->update(['password' => Hash::make($password)]);

            if (settings('sms_enabled')) {
                SendForgotPasswordSMS::dispatch($member, $password);
            }

            if (settings('email_enabled')) {
                $title = 'Forgot Password';
                $body = sprintf(
                    'We have received a reset password request. Please login with your new password %s.',
                    $password,
                );
                $user = [
                    'name' => $member->user->name,
                    'email' => $member->user->email,
                ];

                Mail::to($user['email'])->send(new SendGeneralMail($user, $title, $body));
            }

            return redirect()
                ->route('user.login.create')
                ->with(['success' => 'Password sent successfully to your registered Mobile Number']);
        } else {
            return redirect()->back()->with('error', 'Member ID is invalid');
        }
    }
}
