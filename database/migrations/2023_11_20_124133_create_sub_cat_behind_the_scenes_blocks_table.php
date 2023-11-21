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
        Schema::create('sub_cat_behind_the_scenes_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->string('video_path')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete("cascade");
            $table->foreignId('article_id')->constrained('articles')->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_cat_behind_the_scenes_blocks');
    }
};
