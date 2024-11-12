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
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')
                ->nullable()
                ->constrained();
            $table->foreignId('from_member_id')
                ->nullable()
                ->constrained('members');
            $table->foreignId('package_id')
                ->nullable()
                ->constrained();
            $table->foreignId('user_deposit_id')->nullable()->constrained();
            $table->decimal('amount', 30, 18)->default(0);
            $table->integer('status')
                ->default(\App\Models\TopUp::STATUS_PENDING)
                ->comment('1 = pending | 2 = success');
            $table->integer('done_by')
                ->default(\App\Models\TopUp::DONE_BY_WEB3)
                ->nullable()
                ->comment('1 = admin | 2 = member | 3 :web3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_ups');
    }
};
