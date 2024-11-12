<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBinaryMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binary_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->decimal('amount', 30, 18);
            $table->decimal('admin_charge', 30, 18)->default(0);
            $table->decimal('tds', 30, 18)->default(0);
            $table->decimal('total', 30, 18);
            $table->boolean('capping_reached');
            $table->decimal('left_members', 30, 18);
            $table->decimal('right_members', 30, 18);
            $table->decimal('left_total_bv', 30, 18);
            $table->decimal('right_total_bv', 30, 18);
            $table->decimal('left_forward_bv', 30, 18);
            $table->decimal('right_forward_bv', 30, 18);
            $table->decimal('left_new_bv', 30, 18);
            $table->decimal('right_new_bv', 30, 18);
            $table->decimal('left_completed_bv', 30, 18);
            $table->decimal('right_completed_bv', 30, 18);
            $table->boolean('is_show')->default(true);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binary_matches');
    }
}
