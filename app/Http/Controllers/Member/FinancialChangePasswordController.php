<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class FinancialChangePasswordController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'financial_old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, Auth::user()->financial_password)) {
                        $fail('Old transaction password is invalid');
                    }
                },
            ],
            'financial_password' => 'bail|required|without_spaces|confirmed|different:financial_old_password',
            'financial_password_confirmation' => 'required|without_spaces',
        ], [
            'financial_old_password.required' => 'The old transaction password is required',
            'financial_password.required' => 'The new transaction password is required',
            'financial_password.confirmed' => 'The confirm transaction password does not match',
            'financial_password.without_spaces' => 'Space not allowed in Transaction Password',
            'financial_password_confirmation.without_spaces' => 'Space not allowed in Confirm Transaction Password',
            'financial_password.regex' => 'Enter at least one character and one number',
            'financial_password.different' => 'The new transaction password cannot be the same as the previous password. Please choose a different password',
        ]);

        $this->user->update([
            'financial_password' => Hash::make($request->get('financial_password')),
        ]);

        return redirect()->back()->with(['success' => 'Transaction password changed successfully']);
    }
}
