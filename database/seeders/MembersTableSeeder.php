<?php

namespace Database\Seeders;

use App\Models\Member;
use DB;
use Illuminate\Database\Seeder;
use Throwable;

class MembersTableSeeder extends Seeder
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
            for ($i = 0; $i <= 50; $i++) {
                Member::factory()->create();
            }
        });
    }
}
