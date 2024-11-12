<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class ChangePasswordController extends Controller
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
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! Hash::check($value, Auth::user()->password)) {
                        $fail('Old password is invalid');
                    }
                },
            ],
            'password' => 'bail|required|without_spaces|confirmed|different:old_password',
            'password_confirmation' => 'required|without_spaces',
        ], [
            'old_password.required' => 'The old password is required',
            'password.required' => 'The new password is required',
            'password.confirmed' => 'The confirm password does not match',
            'password.without_spaces' => 'Space not allowed in Password',
            'password_confirmation.without_spaces' => 'Space not allowed in confirm password',
            'password.regex' => 'Enter at least one character and one number',
            'password.different' => 'The new password cannot be the same as the previous password, Please choose a different password',
        ]);

        if (Hash::check($request->get('password'), $this->member->user->password)) {
            return redirect()->back()->with(['error' => 'The new password cannot be the same as the previous password, Please choose a different password']);
        }

        $this->user->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect()->back()->with(['success' => 'Password changed successfully']);
    }
}
