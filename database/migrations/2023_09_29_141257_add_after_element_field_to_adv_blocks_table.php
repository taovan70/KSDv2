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
        Schema::table('adv_blocks', function (Blueprint $table) {
            $table->string('after_element')->after('number_of_elements_from_beginning');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adv_blocks', function (Blueprint $table) {
            $table->dropColumn('after_element');
        });
    }
};
