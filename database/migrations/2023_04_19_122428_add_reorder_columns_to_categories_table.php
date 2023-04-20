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
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('slug');
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('lft')->default(0)->after('slug');
            $table->unsignedBigInteger('rgt')->default(0)->after('slug');
            $table->unsignedBigInteger('depth')->default(0)->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('lft');
            $table->dropColumn('rgt');
            $table->dropColumn('depth');
        });
    }
};
