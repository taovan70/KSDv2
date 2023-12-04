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
        Schema::table('q_a_categories', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropColumn('article_id');
            $table->dropColumn('lft');
            $table->dropColumn('rgt');
            $table->foreignId('category_id')->nullable()->after('name')->constrained('categories')->onDelete("cascade");
            $table->foreignId('article_one_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_two_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_three_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_four_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_five_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('article_six_id')->after('name')->nullable()->constrained('articles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('q_a_categories', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropForeign(['article_one_id']);
            $table->dropColumn('article_one_id');
            $table->dropForeign(['article_two_id']);
            $table->dropColumn('article_two_id');
            $table->dropForeign(['article_three_id']);
            $table->dropColumn('article_three_id');
            $table->dropForeign(['article_four_id']);
            $table->dropColumn('article_four_id');
            $table->dropForeign(['article_five_id']);
            $table->dropColumn('article_five_id');
            $table->dropForeign(['article_six_id']);
            $table->dropColumn('article_six_id');
            $table->foreignId('article_id')->after('name')->constrained('articles')->cascadeOnDelete();
            $table->unsignedBigInteger('lft')->default(0)->after('name');
            $table->unsignedBigInteger('rgt')->default(0)->after('name');
        });

    }
};
