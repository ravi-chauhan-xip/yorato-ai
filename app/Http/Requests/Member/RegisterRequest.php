<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        return [
            'side' => 'required',
            'walletAddress' => 'required|without_spaces|unique:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
            'referralWalletAddress' => 'required|without_spaces|exists:users,wallet_address|regex:/^0x[a-fA-F0-9]{40}$/|bail',
        ];
    }

    public function messages(): array
    {
        return [
            'referralWalletAddress.exists' => 'The selected referral wallet address not found',
            'referralWalletAddress.required' => 'The referral wallet address is required',
            'walletAddress.required' => 'The wallet address is required',
            'referralWalletAddress.regex' => 'The referral wallet address format is invalid',
            'walletAddress.regex' => 'The wallet address format is invalid',
            'walletAddress.unique' => 'The wallet address already exists',
            'walletAddress.without_spaces' => 'The wallet address cannot contain white spaces',
            'referralWalletAddress.without_spaces' => 'The referral wallet address cannot contain white spaces',
        ];
    }
}
