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
        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); 
            $table->decimal('amount_bs', 16, 2)->default(0);
            $table->decimal('amount_usd', 10, 2)->default(0); 
            $table->decimal('total_cash_usd', 10, 2)->default(0); 
            $table->decimal('exchange_rate', 10, 2); 
            $table->text('notes')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
    }
};
