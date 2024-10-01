<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');  // Foreign key to apps
            $table->string('name');
            $table->decimal('price', 8, 2);  // Price with 2 decimal places
            $table->decimal('discount', 5, 2)->nullable();
            $table->string('billing_cycle')->default('monthly');  // Monthly, yearly, etc.
            $table->timestamps();

            $table->index('app_id');  // Index for app_id for quicker searches
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }

};
