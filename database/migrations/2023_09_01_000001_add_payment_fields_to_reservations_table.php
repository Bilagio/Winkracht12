<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_reference')->nullable()->after('notes');
            $table->string('payment_method')->nullable()->after('payment_reference');
            $table->timestamp('payment_confirmed_at')->nullable()->after('payment_method');
            $table->foreignId('payment_confirmed_by')->nullable()->after('payment_confirmed_at')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['payment_confirmed_by']);
            $table->dropColumn(['payment_reference', 'payment_method', 'payment_confirmed_at', 'payment_confirmed_by']);
        });
    }
}
