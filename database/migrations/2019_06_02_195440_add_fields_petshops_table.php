<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPetshopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('petshops', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('description');
            $table->double('rating_average', 2, 1)->default(0)->after('logo');
            $table->integer('max_discount')->default(0)->after('rating_average');
            $table->integer('num_services')->default(0)->after('max_discount');
            $table->text('schedule')->nullable()->after('num_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('petshops', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('rating_average');
            $table->dropColumn('max_discount');
            $table->dropColumn('num_services');
            $table->dropColumn('schedule');
        });
    }
}
