<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetshopImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petshop_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('petshop_id');
            $table->foreign('petshop_id')
                    ->references('id')
                    ->on('petshops')
                    ->onDelete('cascade');
            $table->text('image');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('petshop_images');
    }
}
