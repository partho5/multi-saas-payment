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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // because it's not the laravel app's user_id, rather userId from external app is used for payment.
            $table->string('transac_id')->nullable();
            $table->string('package_code')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_id')->nullable();
            $table->string('payer_country')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('status')->nullable();
            $table->integer('recurring_duration')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->timestamp('last_payment_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
