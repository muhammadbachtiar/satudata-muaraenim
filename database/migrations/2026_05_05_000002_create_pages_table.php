<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Add foreign key on nav_menus after pages table exists
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
        });
        Schema::dropIfExists('pages');
    }
};
