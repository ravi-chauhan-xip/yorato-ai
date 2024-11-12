<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ListBuilders\Admin\MemberListBuilder;
use App\ListBuilders\Admin\MemberStatusLogListBuilder;
use App\Models\Member;
use App\Models\User;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\RoundingNecessaryException;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MemberController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        return MemberListBuilder::render();
    }

    public function show(Member $member): Member
    {
        return $member->load('user');
    }

    /**
     * @throws MathException
     * @throws RoundingNecessaryException
     */
    public function walletDetail($walletAddress): JsonResponse
    {
        $user = User::whereWalletAddress($walletAddress)->first();

        return response()->json([
            'status' => true,
            'walletAddress' => substr($user->wallet_address, 0, 5).'...'.substr($user->wallet_address, -5),
            'wallet_balance' => toHumanReadable($user->member->wallet_balance),
        ]);
    }

    public function edit(Member $member): Renderable
    {
        return view('admin.members.edit', ['member' => $member]);
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns|unique:users,email,'.$member->user->id,
        ], [
            'email.required' => 'The Email ID is required',
            'email.email' => 'The Email ID must be a valid format',
            'email.unique' => 'The Email ID has already been taken',
        ]);

        $member->user->email = $request->get('email');
        $member->user->save();

        return redirect()->route('admin.users.index')->with(['success' => 'Member details updated successfully']);
    }

    /**
     * @throws Exception
     */
    public function memberLog(Request $request, Member $member): Renderable|JsonResponse|RedirectResponse
    {
        return MemberStatusLogListBuilder::render([
            'member_id' => $this->member->id,
        ]);
    }
}
