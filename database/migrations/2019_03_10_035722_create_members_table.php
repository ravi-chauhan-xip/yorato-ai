<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('sponsor_id')->nullable();
            $table->unsignedBigInteger('left_id')->nullable();
            $table->unsignedBigInteger('right_id')->nullable();
            //            $table->string('code')->unique()->nullable();
            $table->integer('parent_side')->nullable()->comment('1: Left, 2: Right');
            $table->integer('left_count')->default(0);
            $table->integer('right_count')->default(0);
            $table->decimal('left_bv', 30, 18)->default(0);
            $table->decimal('right_bv', 30, 18)->default(0);
            $table->decimal('left_power', 30, 18)->default(0);
            $table->decimal('right_power', 30, 18)->default(0);
            $table->decimal('left_stake_bv', 30, 18)->default(0);
            $table->decimal('right_stake_bv', 30, 18)->default(0);
            $table->decimal('left_stake_power', 30, 18)->default(0);
            $table->decimal('right_stake_power', 30, 18)->default(0);
            $table->integer('sponsored_left')->default(0);
            $table->integer('sponsored_right')->default(0);
            $table->longText('path')->nullable();
            $table->longText('sponsor_path')->nullable();
            $table->integer('level')->index();
            $table->integer('sponsor_level')->index();
            $table->integer('sponsored_count')->default(0);
            $table->decimal('wallet_balance', 30, 18)->default(0);
            $table->tinyInteger('is_paid')->default(1)->comment('1 :un paid, 2 :paid');
            $table->integer('status')
                ->comment('1: Free Member, 2: Active, 3: Block')
                ->index();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sponsor_id')->references('id')->on('members');
            $table->foreign('parent_id')->references('id')->on('members');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
