<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Validator;

class PasswordController extends Controller
{
    public function edit(): Factory|View
    {
        return view('admin.password.edit');
    }

    /**
     * Update the specified resource in storage.
     *
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
                    if (! Hash::check($value, $this->admin->password)) {
                        $fail('Old Password Is Invalid');
                    }
                },
            ],
            'password' => 'bail|required|without_spaces|confirmed|different:old_password',
            'password_confirmation' => 'required|without_spaces',
        ], [
            'old_password.required' => 'The old password is required',
            'password.required' => 'The new password is required',
            'password.confirmed' => 'The confirm password does not match',
            'password.without_spaces' => 'The new password cannot contain white spaces',
            'password_confirmation.without_spaces' => 'The confirm password cannot contain white spaces',
            'password_confirmation.required' => 'The confirm password is required',
            'password.different' => 'The new password cannot be the same as the previous password, Please choose a different password',
        ]);

        $this->admin->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Password changed successfully');
    }
}
