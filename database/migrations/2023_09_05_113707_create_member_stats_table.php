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
        Schema::create('member_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained();
            $table->decimal('staking_income', 30, 18)->index()->default(0);
            $table->decimal('direct_income', 30, 18)->index()->default(0);
            $table->decimal('direct_sponsor_staking', 30, 18)->index()->default(0);
            $table->decimal('team_matching', 30, 18)->index()->default(0);
            $table->decimal('team_matching_staking', 30, 18)->index()->default(0);
            $table->decimal('leadership_bonus', 30, 18)->index()->default(0);
            $table->decimal('admin_credit', 30, 18)->index()->default(0);
            $table->decimal('all_income', 30, 18)->index()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_stats');
    }
};
