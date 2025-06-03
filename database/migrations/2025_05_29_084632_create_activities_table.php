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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('type', ['seminar', 'webinar', 'mentoring', 'lomba', 'workshop', 'training']);
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->text('location');
            $table->string('city');
            $table->string('province');
            $table->enum('format', ['online', 'offline', 'hybrid']);
            $table->string('meeting_link')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_deadline');
            $table->json('requirements')->nullable();
            $table->json('benefits')->nullable();
            $table->json('images')->nullable();
            $table->integer('max_participants');
            $table->integer('current_participants')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
