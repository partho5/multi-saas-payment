<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->string('feature_name');
            $table->boolean('availability')->default(true);  // True if feature is available
            $table->timestamps();

            $table->index(['app_id', 'package_id']);  // Composite index for app and package
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};
