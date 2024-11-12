<?php

use App\Models\Package;
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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 30, 18)->default(0);
            $table->decimal('staking_min', 30, 18)->default(0);
            $table->decimal('staking_max', 30, 18)->default(0);
            $table->decimal('capping', 30, 18)->nullable();
            $table->integer('status')
                ->comment('1: Active, 2: In-Active')
                ->default(Package::STATUS_ACTIVE)
                ->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
