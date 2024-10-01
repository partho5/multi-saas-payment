<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('app_code')->unique();  // SEO-friendly identifier
            $table->string('app_name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('app_code');  // Index for faster lookups
        });
    }

    public function down()
    {
        Schema::dropIfExists('apps');
    }
};
