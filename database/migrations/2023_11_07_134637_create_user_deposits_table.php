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
        Schema::create('user_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained();
            $table->foreignId('package_id')->constrained();
            $table->bigInteger('order_no')->unique();
            $table->decimal('amount', 30, 18);
            $table->string('block_no')->nullable();
            $table->string('from_address')->index();
            $table->string('to_address')->index();
            $table->string('transaction_hash')->unique()->index();
            $table->integer('blockchain_status')
                ->default(\App\Models\UserDeposit::BLOCKCHAIN_STATUS_PENDING)
                ->comment('1 = pending | 2 = completed | 3 = failed');
            $table->text('remark')
                ->nullable();
            $table->json('receipt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_deposits');
    }
};
