<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews_reviews', function (Blueprint $table) {
            // Imported reviews have no site account, and listings don't always
            // provide a title or a rating.
            $table->unsignedInteger('author_id')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->integer('rating')->nullable()->change();

            $table->string('type')->default('local')->after('id');
            $table->string('source')->nullable()->after('type');
            $table->string('source_id')->nullable()->after('source');
            $table->string('source_url')->nullable()->after('source_id');
            $table->string('author_name')->nullable()->after('author_id');

            $table->unique(['source', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('reviews_reviews')->whereNotNull('source')->delete();

        Schema::table('reviews_reviews', function (Blueprint $table) {
            $table->dropUnique(['source', 'source_id']);
            $table->dropColumn(['type', 'source', 'source_id', 'source_url', 'author_name']);

            $table->unsignedInteger('author_id')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
            $table->integer('rating')->nullable(false)->change();
        });
    }
};
