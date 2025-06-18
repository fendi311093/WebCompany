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
        Schema::create('navigation_webs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['header', 'dropdown']);
            $table->string('title')->unique();
            $table->string('slug');
            $table->foreignId('parent_id')->nullable()->constrained('navigation_webs')->cascadeOnDelete();
            $table->tinyInteger('position');
            $table->boolean('is_active_page')->default(true);
            $table->foreignId('page_id')->nullable()->constrained('pages')->cascadeOnDelete();
            $table->boolean('is_active_link')->default(false);
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigation_webs');
    }
};
