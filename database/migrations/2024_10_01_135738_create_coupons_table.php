<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            $table->string('code')->unique();  // Coupon code
            $table->decimal('discount', 5, 2);  // Discount percentage
            $table->timestamp('valid_until')->nullable();  // Expiration date
            $table->timestamps();

            $table->index('app_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
