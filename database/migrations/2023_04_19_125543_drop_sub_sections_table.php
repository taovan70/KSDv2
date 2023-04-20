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
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['sub_section_id']);
            $table->dropColumn('sub_section_id');
        });
        Schema::table('author_sub_section', function (Blueprint $table) {
            $table->dropForeign(['sub_section_id']);
            $table->dropColumn('sub_section_id');
        });
        Schema::dropIfExists('sub_sections');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
