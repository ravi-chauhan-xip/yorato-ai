<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AddMemberOnNetwork;
use App\Jobs\CalculateDirectSponsorStakingIncome;
use App\Jobs\CalculateDirectWalletIncome;
use App\Jobs\UpgradeStakeOnNetwork;
use App\Jobs\UpgradeTopUpOnNetwork;
use App\ListBuilders\Admin\AdminListBuilder;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Package;
use App\Models\StakeCoin;
use App\Models\TopUp;
use App\Models\User;
use App\Models\UserDeposit;
use App\Models\WalletTransaction;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Throwable;
use Validator;

class AdminController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): Renderable|JsonResponse|RedirectResponse
    {
        return AdminListBuilder::render();
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'name' => [
                'required',
                'unique:admins',
            ],
            'email' => [
                'required', 'email:rfc,dns',
                'unique:admins',
            ],
            'mobile' => [
                'required', 'regex:/^[6789][0-9]{9}$/',
                'unique:admins',
            ],
            'is_super' => 'required',
            'permissions' => 'required_if:is_super,===,0|array',
            'permissions.*' => 'required_if:is_super,===,0|exists:permissions,name',
            'password' => 'required|without_spaces',
        ], [
            'mobile.required' => 'The mobile number is required',
            'mobile.regex' => 'The mobile number format is invalid',
            'mobile.unique' => 'Mobile number already exists',
            'is_super.required' => 'The is super admin required',
            'permissions.required_if' => 'The permissions is required when is super admin is No',
            'permissions.*.required_if' => 'The permissions is required when is super admin is No',
            'email.email' => 'The email must be a valid format',
            'email.unique' => 'Email already exists',
            'permissions.required' => 'At least one permission is required',
            'password.without_spaces' => 'The password cannot contain white spaces',
            'password.required' => 'The new password is required',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $admin = Admin::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'mobile' => $request->get('mobile'),
                    'is_super' => $request->get('is_super') == true,
                    'password' => Hash::make($request->get('password')),
                    'status' => Admin::STATUS_ACTIVE,
                    'creator_id' => $this->admin->id,
                ]);

                if ($request->get('is_super') == 0) {
                    $admin->givePermissionTo($request->get('permissions'));
                }

                return redirect()->route('admin.admins.index')->with(['success' => 'Admin added successfully']);
            });
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    public function create(): Factory|View|Application
    {
        $permissions = Permission::all()
            ->groupBy(function (Permission $permission) {
                return preg_replace('/-(create|read|update|delete)/', '', $permission->name);
            });

        return view('admin.admins.create', [
            'permissions' => $permissions,
        ]);
    }

    public function edit(Admin $admin): Renderable|RedirectResponse
    {
        if ($admin->id === 1) {
            return redirect()->route('admin.admins.index')->with(['error' => 'Invalid Admin']);
        }

        $permissions = Permission::all()
            ->groupBy(function (Permission $permission) {
                return preg_replace('/-(read|create|update|delete)/', '', $permission->name);
            });

        return view('admin.admins.edit', [
            'admin' => $admin,
            'permissions' => $permissions,
        ]);
    }

    public function changePassword(Admin $admin): Factory|View|Application
    {
        return view('admin.admins.change-password', ['admin' => $admin]);
    }

    /**
     * @throws ValidationException
     */
    public function changePasswordUpdate(Admin $admin, Request $request): RedirectResponse
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $this->validate($request, [
            'password' => 'required|without_spaces|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'The new password is required',
            'password_confirmation.required' => 'The confirm new password is required',
            'password.without_spaces' => 'Space not allowed in Password',
            'password_confirmation.without_spaces' => 'Space not allowed in Confirm Password',
        ]);
        try {
            $password = $request->get('password');
            $admin->update([
                'password' => Hash::make($password),
            ]);

            return redirect()->route('admin.admins.index')->with(['success' => 'Password changed successfully']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    /**
     * @throws ValidationException
     */
    public function update(Admin $admin, Request $request): JsonResponse|RedirectResponse
    {
        if ($admin->id === 1) {
            return redirect()->route('admin.admins.index')->with(['error' => 'Invalid User']);
        }

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:admins,email,'.$admin->id,
            'mobile' => 'required|regex:/^[6789][0-9]{9}$/|unique:admins,mobile,'.$admin->id,
            'is_super' => 'required',
            'permissions' => 'required_if:is_super,===,0|array',
            'permissions.*' => 'required_if:is_super,===,0|exists:permissions,name',
        ], [
            'mobile.required' => 'The mobile number is required',
            'mobile.regex' => 'The mobile number format is invalid',
            'mobile.unique' => 'Mobile number already exists',
            'is_super.required' => 'The is super admin required',
            'permissions.required_if' => 'The permissions is required when is super admin is No',
            'permissions.*.required_if' => 'The permissions is required when is super admin is No',
            'email.email' => 'The email must be a valid format',
            'email.unique' => 'Email already exists',
            'permissions.required' => 'At least one permission is required',
        ]);
        try {
            return DB::transaction(function () use ($request, $admin) {
                $admin->name = $request->get('name');
                $admin->email = $request->get('email');
                $admin->mobile = $request->get('mobile');
                $admin->is_super = $request->get('is_super') == true;
                $admin->save();

                if ($request->get('is_super') == 0) {
                    $admin->syncPermissions($request->get('permissions'));
                }

                return redirect()->route('admin.admins.index')->with(['success' => 'Admin update successfully']);
            });
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    public function updateStatus(Admin $admin): JsonResponse|RedirectResponse
    {
        try {
            if ($admin->status == Admin::STATUS_ACTIVE) {
                $admin->status = Admin::STATUS_IN_ACTIVE;
            } else {
                $admin->status = Admin::STATUS_ACTIVE;
            }
            $admin->save();

            return redirect()->route('admin.admins.index')->with(['success' => 'Successfully Status changed']);
        } catch (Throwable $e) {
            return $this->logExceptionAndRespond($e);
        }
    }

    public function addMember(Request $request)
    {
        DB::transaction(function () use ($request) {
            $package = Package::first();
            $sponsor = Member::whereHas('user', function ($q) use ($request) {
                return $q->where('wallet_address', $request->sponsor);
            })->first();

            if (! $sponsor) {
                echo 'sponsor invalid';
                exit();
            }

            $side = $request->get('side');

            if (! $side) {
                echo 'side required';
                exit();
            }

            $user = User::create([
                'wallet_address' => str_random(16),
            ]);

            Auth::shouldUse('member');
            $user->assignRole('member');

            if ($side == Member::PARENT_SIDE_LEFT) {
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

            $member = Member::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'sponsor_id' => $sponsor->id,
                'parent_id' => $parent->id,
                'level' => $parent->level + 1,
                'sponsor_level' => $sponsor->sponsor_level + 1,
                'status' => Member::STATUS_ACTIVE,
                'activated_at' => Carbon::now(),
                'is_paid' => Member::IS_PAID,
                'wallet_balance' => 0,
                'parent_side' => $side,
            ]);

            $deposit = UserDeposit::create([
                'member_id' => $user->member->id,
                'package_id' => $member->package_id,
                'order_no' => UserDeposit::generateRandomOrderNo(),
                'block_no' => 0,
                'from_address' => '0x6AC7F23fda904b2232Fde036bA6049cC5bba7270',
                'to_address' => '0x6AC7F23fda904b2232Fde036bA6049cC5bba7270',
                'amount' => $package->amount,
                'transaction_hash' => str_random(),
            ]);

            $deposit->walletTransaction()->create([
                'member_id' => $deposit->member->id,
                'opening_balance' => $user->member->wallet_balance,
                'closing_balance' => $user->member->wallet_balance + $deposit->amount,
                'amount' => $deposit->amount,
                'total' => $deposit->amount,
                'comment' => 'Get deposit transaction from block chain',
                'type' => WalletTransaction::TYPE_CREDIT,
            ]);

            $topUp = TopUp::create([
                'member_id' => $member->id,
                'amount' => $package->amount,
                'user_deposit_id' => $deposit->id,
                'package_id' => $package->id,
            ]);

            $topUp->walletTransaction()->create([
                'member_id' => $member->id,
                'opening_balance' => $member->wallet_balance,
                'closing_balance' => $member->wallet_balance - $topUp->amount,
                'amount' => $topUp->amount,
                'total' => $topUp->amount,
                'comment' => "Topup applied for {$member->user->wallet_address}",
                'type' => WalletTransaction::TYPE_DEBIT,
            ]);

            $stakeAmount = 10;

            $deposit = UserDeposit::create([
                'member_id' => $member->id,
                'order_no' => UserDeposit::generateRandomOrderNo(),
                'package_id' => $member->package_id,
                'amount' => $stakeAmount,
                'transaction_hash' => str_random(),
                'from_address' => env('VITE_USDT_DEPOSIT_WALLET_ADDRESS'),
                'to_address' => env('VITE_USDT_DEPOSIT_WALLET_ADDRESS'),
                'blockchain_status' => UserDeposit::BLOCKCHAIN_STATUS_COMPLETED,
            ]);

            $deposit->walletTransaction()->create([
                'member_id' => $deposit->member->id,
                'opening_balance' => $user->member->wallet_balance,
                'closing_balance' => $user->member->wallet_balance + $deposit->amount,
                'amount' => $deposit->amount,
                'total' => $deposit->amount,
                'comment' => 'Get stake transaction from block chain',
                'type' => WalletTransaction::TYPE_CREDIT,
            ]);

            $stake = StakeCoin::create([
                'member_id' => $member->id,
                'user_deposit_id' => $deposit->id,
                'package_id' => $member->package_id,
                'amount' => $stakeAmount,
                'capping_days' => settings('capping_days'),
                'remaining_days' => settings('capping_days'),
                'status' => StakeCoin::STATUS_ACTIVE,
            ]);

            $stake->walletTransaction()->create([
                'member_id' => $member->id,
                'opening_balance' => $member->wallet_balance,
                'closing_balance' => $member->wallet_balance - $stake->amount,
                'amount' => $stake->amount,
                'total' => $stake->amount,
                'comment' => "Stake applied for {$member->user->wallet_address}",
                'type' => WalletTransaction::TYPE_DEBIT,
            ]);

            AddMemberOnNetwork::dispatch($member);
            CalculateDirectWalletIncome::dispatch($topUp);
            UpgradeTopUpOnNetwork::dispatch($topUp);

            CalculateDirectSponsorStakingIncome::dispatch($stake);
            UpgradeStakeOnNetwork::dispatch($stake);
        });
    }
}
