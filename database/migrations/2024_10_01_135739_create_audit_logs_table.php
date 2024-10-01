<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('app_id')->constrained('apps')->onDelete('cascade');
            $table->string('action');  // e.g., "purchase", "cancellation"
            $table->timestamps();

            $table->index('user_id');
            $table->index('app_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
