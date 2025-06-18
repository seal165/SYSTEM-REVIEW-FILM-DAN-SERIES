<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('showtime_id')->constrained()->onDelete('cascade');
            $table->string('booking_reference')->unique();
            $table->integer('seats_booked');
            $table->decimal('total_amount', 10, 2);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->enum('payment_method', ['cash', 'card', 'online'])->default('online');
            $table->enum('status', ['confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamp('booking_date');
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['showtime_id', 'status']);
            $table->index('booking_reference');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};