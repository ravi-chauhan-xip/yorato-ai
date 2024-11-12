<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Admin\AdminSendForgotPasswordSMS;
use App\Mail\SendGeneralMail;
use App\Models\Admin;
use Closure;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Mail;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function create(): Factory|View
    {
        return view('admin.forget-password.create');
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function Store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'emailOrMobile' => [
                'required',
                function (string $attribute, mixed $value, Closure $fail) {
                    $validator = new EmailValidator;

                    if (! $validator
                        ->isValid(
                            $value,
                            new MultipleValidationWithAnd([
                                new DNSCheckValidation,
                                new RFCValidation,
                            ])
                        )
                    ) {
                        $fail('The Email ID must be a valid format');
                    }
                },
            ],
        ], [
            'emailOrMobile.required' => 'Email ID is required',
        ]);

        try {
            if ($admin = Admin::where('email', $request->get('emailOrMobile'))
                ->orWhere('mobile', $request->get('emailOrMobile'))
                ->first()
            ) {
                if ($admin->roles()->where('name', 'admin')->first()) {
                    $password = mt_rand(11111111, 99999999);

                    $admin->update(['password' => Hash::make($password)]);

                    if (settings('sms_enabled')) {
                        AdminSendForgotPasswordSMS::dispatch($admin, $password);
                    }

                    if (settings('email_enabled')) {
                        $title = 'Forgot Password';
                        $body = sprintf(
                            'We have received a reset password request. Please login with your new password %s.',
                            $password,
                        );
                        $AdminUserMail = [
                            'name' => $admin->name,
                            'email' => $admin->email,
                        ];

                        Mail::to($admin->email)->send(new SendGeneralMail($AdminUserMail, $title, $body));
                    }

                    return redirect()
                        ->route('admin.login.create')
                        ->with(['success' => 'Password sent successfully to your registered Email ID']);
                } else {
                    return redirect()->back()->with('error', 'The Email ID is invalid');
                }
            } else {
                return redirect()->back()->with('error', 'The Email ID is invalid');
            }
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }
}
