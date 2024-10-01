<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('status')->default('pending');  // Payment status
            $table->string('token')->unique();  // Unique token for the payment
            $table->timestamp('expires_at')->nullable();  // Token expiration (optional)
            $table->timestamps();

            $table->index('user_id');
            $table->index('app_id');
            $table->index('package_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_purchases');
    }
};
