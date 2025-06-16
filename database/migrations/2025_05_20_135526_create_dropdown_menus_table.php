<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dropdown_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('headerButton_id')->constrained('header_buttons')->cascadeOnDelete();
            $table->tinyInteger('position');
            $table->foreignId('page_id')->nullable()->constrained('pages')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_active_url')->default(false);
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdown_menus');
    }
};
