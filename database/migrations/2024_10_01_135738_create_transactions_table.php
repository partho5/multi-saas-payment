<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('payment_method');  // Payment method (e.g., credit card, PayPal)
            $table->decimal('amount', 10, 2);  // Transaction amount
            $table->string('currency', 3)->default('USD');  // Currency code (e.g., USD)
            $table->string('status')->default('pending');  // Transaction status
            $table->timestamps();

            $table->index('user_id');
            $table->index('app_id');
            $table->index('package_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
