<?php

namespace Database\Seeders;

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
use Hash;
use Illuminate\Database\Seeder;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * @throws Throwable
     */
    public function run(): void
    {
        DB::transaction(function () {
            if (User::count() == 0) {
                $this->call(CountryTableSeeder::class);
                $this->call(StatesCitiesTableSeeder::class);
                $this->call(RolesTableSeeder::class);
                $this->call(PackageTableSeeder::class);
                $this->call(PermissionsTableSeeder::class);
                $this->call(SettingsTableSeeder::class);

                $user = Admin::create([
                    'name' => 'PoloGain',
                    'email' => 'pologain@mail.com',
                    'mobile' => '9999999999',
                    'password' => Hash::make('company@123'),
                    'is_super' => true,
                ]);

                Auth::shouldUse('admin');
                $user->assignRole('admin');

                // Below wallet_address is default address provide by client
                $user = User::create([
                    'name' => 'PoloGain',
                    'wallet_address' => '0x6AC7F23fda904b2232Fde036bA6049cC5bba7270',
                ]);

                Auth::shouldUse('member');
                $user->assignRole('member');

                $package = Package::orderBy('id', 'desc')->first();

                $member = Member::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'sponsor_id' => null,
                    'level' => 1,
                    'sponsor_level' => 1,
                    'status' => Member::STATUS_ACTIVE,
                    'path' => '1',
                    'sponsor_path' => '1',
                    'activated_at' => Carbon::now(),
                    'is_paid' => Member::IS_PAID,
                    'wallet_balance' => 0,
                ]);

                $deposit = UserDeposit::create([
                    'member_id' => $user->member->id,
                    'package_id' => $member->package_id,
                    'order_no' => UserDeposit::generateRandomOrderNo(),
                    'block_no' => 0,
                    'from_address' => '0x6AC7F23fda904b2232Fde036bA6049cC5bba7270',
                    'to_address' => '0x6AC7F23fda904b2232Fde036bA6049cC5bba7270',
                    'amount' => $package->amount,
                    'transaction_hash' => '0x0000000000000000000000000000000000000000000000000000000000000000',
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
                    'status' => TopUp::STATUS_SUCCESS,
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

                $stakeAmount = 500;

                $deposit = UserDeposit::create([
                    'member_id' => $member->id,
                    'order_no' => UserDeposit::generateRandomOrderNo(),
                    'package_id' => $member->package_id,
                    'amount' => $stakeAmount,
                    'transaction_hash' => '00XX',
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
                    'capping_days' => 900,
                    'remaining_days' => 900,
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

            }

            if (env('APP_ENV') != 'production') {
                $this->call(MemberLoginLogTableSeeder::class);
            }
        });
    }
}
