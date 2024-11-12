<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Session;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                return redirect()->route('user.dashboard.index');
            }

            return $next($request);
        })->only('create', 'store');
    }

    public function create(): Factory|View
    {
        return view('member.login.create');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'code' => 'required',
            'password' => 'required',
            'remember' => 'boolean',
        ], [
            'code.required' => 'The Member ID is required',
            'password.required' => 'The password is required',
        ]);

        if ($member = Member::whereCode($request->get('code'))->first()) {
            if ($member->isBlocked()) {
                return redirect()->back()->with(['error' => 'Member ID is blocked'])->withInput();
            }

            if (Hash::check($request->get('password'), $member->user->password)) {
                Auth::login($member->user, $request->get('remember'));

                if ($ip = $request->ip()) {
                    $member->loginLogs()->create([
                        'ip' => $ip,
                    ]);
                }

                if (Session::get('getProduct')) {
                    $product = Session::get('getProduct');

                    return redirect()->route('website.product.detail', $product);
                } else {
                    return redirect()->route('user.dashboard.index');
                }
            }
        }

        return redirect()->back()->with(['error' => 'Member ID or Password is incorrect'])->withInput();
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('user.register.create');
    }

    public function checkWalletAddress(Request $request, $walletAddress): \Illuminate\Http\JsonResponse
    {
        $user = User::whereWalletAddress(strtolower($walletAddress))
            ->first();

        if ($user) {

            if ($user->member->isBlocked()) {
                return response()->json([
                    'status' => false,
                    'isBlocked' => true,
                    'message' => 'User is blocked',
                ]);
            }

            Auth::login($user);

            if ($ip = $request->ip()) {
                $user->member->loginLogs()->create([
                    'ip' => $ip,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'user found',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'user not found',
            ]);
        }
    }
}
