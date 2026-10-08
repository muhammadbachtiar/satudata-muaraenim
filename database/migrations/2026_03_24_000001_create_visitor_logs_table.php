<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('page', 255)->default('/');
            $table->string('user_agent', 500)->nullable();
            $table->date('visited_date');
            $table->timestamps();

            $table->index('visited_date');
            $table->index(['ip_address', 'visited_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
