<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stake_coins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')
                ->constrained();
            $table->foreignId('from_member_id')
                ->nullable()
                ->constrained('members');
            $table->foreignId('user_deposit_id')
                ->nullable()
                ->constrained();
            $table->foreignId('package_id')
                ->constrained();
            $table->decimal('amount', 30, 18)->default(0);
            $table->integer('capping_days');
            $table->integer('remaining_days');
            $table->integer('status')
                ->default(\App\Models\StakeCoin::STATUS_ACTIVE)
                ->comment('1 = pending | 2 = Finish');
            $table->integer('done_by')
                ->default(\App\Models\StakeCoin::DONE_BY_WEB3)
                ->nullable()
                ->comment('1 = admin | 2 = member | 3 = web3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stake_coins');
    }
};
