<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pet_id');
            $table->foreign('pet_id')
                    ->references('id')
                    ->on('pets')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('turn_id');
            $table->foreign('turn_id')
                    ->references('id')
                    ->on('turns')
                    ->onDelete('cascade');
            $table->enum('status', ['waiting', 'approved', 'concluded', 'canceled'])->default('waiting');
            $table->date('reservation_day');
            $table->timestamps();
        });
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
