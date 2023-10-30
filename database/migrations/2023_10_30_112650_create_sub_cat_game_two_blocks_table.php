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
        Schema::create('sub_cat_game_two_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->json('answer_data');
            $table->foreignId('category_id')->constrained('categories')->onDelete("cascade");
            $table->string('photo_path_one')->nullable();
            $table->string('photo_path_two')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_cat_game_two_blocks');
    }
};
