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
        Schema::create('direct_sponsor_staking_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('from_member_id')->constrained('members');
            $table->foreignId('stake_coin_id')->constrained();
            $table->decimal('stake_amount', 30, 18);
            $table->decimal('percentage');
            $table->decimal('amount', 30, 10);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direct_sponsor_staking_incomes');
    }
};
