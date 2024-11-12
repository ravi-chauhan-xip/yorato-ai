<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Throwable;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws Throwable
     */
    public function run()
    {
        DB::transaction(function () {
            collect([
                [
                    'name' => 'Dashboard',
                ],
                [
                    'name' => 'Admins',
                ],
                [
                    'name' => 'Members',
                ],
                [
                    'name' => 'Deposits',
                ],
                [
                    'name' => 'KYCS',
                ],
                [
                    'name' => 'Package',
                ],
                [
                    'name' => 'Genealogy Tree',
                ],
                [
                    'name' => 'Ecommerce',
                ],
                [
                    'name' => 'Payout',
                ],
                [
                    'name' => 'Member TDS Report',
                ],
                [
                    'name' => 'Wallet',
                ],
                [
                    'name' => 'Buy Coin',
                ],
                [
                    'name' => 'Treasure Wallet',
                ],
                [
                    'name' => 'Reports',
                ],
                [
                    'name' => 'Exports',
                ],
                [
                    'name' => 'GST Manager',
                ],
                [
                    'name' => 'Contact Inquiries',
                ],
                [
                    'name' => 'Support Ticket',
                ],
                [
                    'name' => 'Banking Partner',
                ],
                [
                    'name' => 'Web Settings',
                ],
                [
                    'name' => 'Content Setting',
                ],
            ])->each(function ($permission) {
                Permission::updateOrCreate([
                    'name' => $permission['name'].'-create',
                    'guard_name' => 'admin',
                ]);
                Permission::updateOrCreate([
                    'name' => $permission['name'].'-read',
                    'guard_name' => 'admin',
                ]);
                Permission::updateOrCreate([
                    'name' => $permission['name'].'-update',
                    'guard_name' => 'admin',
                ]);
                Permission::updateOrCreate([
                    'name' => $permission['name'].'-delete',
                    'guard_name' => 'admin',
                ]);
            });
        });
    }
}
