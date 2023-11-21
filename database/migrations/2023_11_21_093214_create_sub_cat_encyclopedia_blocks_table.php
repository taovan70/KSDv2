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
        Schema::create('sub_cat_encyclopedia_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->string('photo_path')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete("cascade");
            $table->foreignId('article_one_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_two_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_three_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_four_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_cat_encyclopedia_blocks');
    }
};
