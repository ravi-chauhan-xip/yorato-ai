<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberLoginLog;
use Illuminate\Database\Seeder;

class MemberLoginLogTableSeeder extends Seeder
{
    public function run(): void
    {
        Member::limit(5)->eachById(function ($member) {
            MemberLoginLog::factory(mt_rand(1, 10))
                ->for($member)
                ->create();
        });
    }
}
