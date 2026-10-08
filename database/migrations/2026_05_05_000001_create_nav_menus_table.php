<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // link, page, route
            $table->string('url')->nullable(); // for external links
            $table->string('route_name')->nullable(); // for internal named routes
            $table->unsignedBigInteger('page_id')->nullable(); // for custom pages
            $table->unsignedBigInteger('parent_id')->nullable(); // for submenus
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('nav_menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_menus');
    }
};
