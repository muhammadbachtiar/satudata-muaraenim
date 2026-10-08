<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type'); // berita, infografis
            $table->longText('body')->nullable();
            $table->string('image')->nullable(); // stored in storage/app/public/publications
            $table->string('excerpt', 500)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
