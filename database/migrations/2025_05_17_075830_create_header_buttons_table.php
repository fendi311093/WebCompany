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
        Schema::create('header_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->tinyInteger('type_button');
            $table->tinyInteger('position_header')->default(0);
            $table->tinyInteger('position_sub_header')->default(0);
            $table->string('name_button')->unique();
            $table->boolean('is_active_button')->default(true);
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
        Schema::dropIfExists('header_buttons');
    }
};
