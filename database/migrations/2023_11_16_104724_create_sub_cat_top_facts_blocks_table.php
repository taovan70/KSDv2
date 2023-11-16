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
        Schema::create('sub_cat_top_facts_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->string('number_one')->nullable();
            $table->text('text_one')->nullable();
            $table->string('number_two')->nullable();
            $table->text('text_two')->nullable();
            $table->string('number_three')->nullable();
            $table->text('text_three')->nullable();
            $table->foreignId('article_one_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_two_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('category_id')->constrained('categories')->onDelete("cascade");
            $table->string('background_photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_cat_top_facts_blocks');
    }
};
