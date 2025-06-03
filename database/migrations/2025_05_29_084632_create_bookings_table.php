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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('bookable');
            $table->json('booking_data');
            $table->json('files')->nullable();
            $table->enum('status', [
                'pending', 'waiting_provider_approval', 'provider_approved', 'provider_rejected',
                'confirmed', 'cancelled', 'completed', 'rejected'
            ])->default('pending');
            $table->datetime('booking_date');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
