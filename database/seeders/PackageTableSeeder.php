<?php

namespace Database\Seeders;

use App\Models\Package;
use DB;
use Illuminate\Database\Seeder;
use Throwable;

class PackageTableSeeder extends Seeder
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
                    'name' => 'Package 1',
                    'amount' => 10,
                    'staking_min' => 10,
                    'staking_max' => 100,
                    'capping' => 30,
                ],
                [
                    'name' => 'Package 2',
                    'amount' => 20,
                    'staking_min' => 10,
                    'staking_max' => 300,
                    'capping' => 60,
                ],
                [
                    'name' => 'Package 3',
                    'amount' => 30,
                    'staking_min' => 10,
                    'staking_max' => 600,
                    'capping' => null,
                ], [
                    'name' => 'Package 4',
                    'amount' => 100,
                    'staking_min' => 10,
                    'staking_max' => 0,
                    'capping' => null,
                ],
            ])->each(function ($package) {
                Package::updateOrCreate([
                    'name' => $package['name'],
                ], [
                    'amount' => $package['amount'],
                    'staking_min' => $package['staking_min'],
                    'staking_max' => $package['staking_max'],
                    'capping' => $package['capping'],
                ]);
            });
        });
    }
}
