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
        Schema::create('everyone_talking_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->unsignedBigInteger('lft')->default(0);
            $table->unsignedBigInteger('rgt')->default(0);
            $table->unsignedBigInteger('depth')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('everyone_talking_abouts');
    }
};
