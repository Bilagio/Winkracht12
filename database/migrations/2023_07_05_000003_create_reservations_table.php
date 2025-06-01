<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table exists before trying to create it
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');
                $table->foreignId('lesson_package_id')->constrained()->onDelete('cascade');
                $table->foreignId('location_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->string('time');
                $table->integer('participants');
                $table->decimal('total_price', 10, 2);
                $table->enum('status', [
                    'pending', 
                    'confirmed', 
                    'cancelled', 
                    'completed', 
                    'instructor_cancelled', 
                    'weather_cancelled'
                ])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
