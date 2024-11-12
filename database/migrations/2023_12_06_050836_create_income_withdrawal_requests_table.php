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
        Schema::create('income_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('company_wallet_id')->nullable();
            $table->string('from_address')->nullable()->index();
            $table->string('to_address')->index();
            $table->decimal('amount', 30, 18);
            $table->decimal('service_charge', 30, 18);
            $table->decimal('total', 30, 18);
            $table->longText('remark');
            $table->string('tx_hash')->nullable()->unique();
            $table->json('receipt')->nullable();
            $table->longText('error')->nullable();
            $table->tinyInteger('status')->comment('1:pending,2:approve,3:reject,4:Processing')->default(\App\Models\IncomeWithdrawalRequest::STATUS_PENDING);
            $table->tinyInteger('blockchain_status')->comment('1:In-checkout,2:pending,3:success,4:fail')->default(\App\Models\IncomeWithdrawalRequest::BLOCKCHAIN_STATUS_PENDING);
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->foreign('company_wallet_id')->references('id')->on('company_wallets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_withdrawal_requests');
    }
};
