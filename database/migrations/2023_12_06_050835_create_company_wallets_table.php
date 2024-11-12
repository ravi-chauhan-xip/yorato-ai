
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
        Schema::create('company_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('private_key');
            $table->decimal('bnb_balance', 30, 18)->default(0)->index();
            $table->decimal('usdt_balance', 30, 18)->default(0)->index();
            $table->decimal('coin_balance', 30, 18)->default(0)->index();
            $table->integer('status')->comment('1 = active | 2 = inactive')->default(1)->index();
            $table->bigInteger('transaction_count')->index()->default(0);
            $table->timestamp('locked_at')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_wallets');
    }
};
