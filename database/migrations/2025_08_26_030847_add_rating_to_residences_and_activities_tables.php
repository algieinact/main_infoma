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
        // Add rating columns to residences table
        if (Schema::hasTable('residences')) {
            Schema::table('residences', function (Blueprint $table) {
                if (!Schema::hasColumn('residences', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(0.00);
                }
                if (!Schema::hasColumn('residences', 'total_reviews')) {
                    $table->integer('total_reviews')->default(0);
                }
            });
        }

        // Add rating columns to activities table
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                if (!Schema::hasColumn('activities', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(0.00);
                }
                if (!Schema::hasColumn('activities', 'total_reviews')) {
                    $table->integer('total_reviews')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove rating columns from residences table
        if (Schema::hasTable('residences')) {
            Schema::table('residences', function (Blueprint $table) {
                $table->dropColumn(['rating', 'total_reviews']);
            });
        }

        // Remove rating columns from activities table
        if (Schema::hasTable('activities')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropColumn(['rating', 'total_reviews']);
            });
        }
    }
};
