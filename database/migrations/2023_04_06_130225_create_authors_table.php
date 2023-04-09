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
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            // Personal data
            $table->string('name');
            $table->string('surname');
            $table->string('middle_name')->nullable();
            $table->integer('age');
            $table->string('gender', 1);
            $table->text('biography');
            $table->string('address');
            $table->string('photo_path');
            // Links
            $table->string('personal_site')->nullable();
            $table->json('social_networks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
