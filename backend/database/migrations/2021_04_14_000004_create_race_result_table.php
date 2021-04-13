<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceResultTable extends Migration
{

    public $tableName = 'race_result';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('race_id')->unsigned();
            $table->integer('rider_id')->unsigned();
            $table->smallInteger('finish_position');
            $table->integer('finish_time');

            $table->foreign('race_id')->references('id')->on('race');
            $table->foreign('rider_id')->references('id')->on('rider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function (BluePrint $table) {
            $table->dropForeign(['race_id']);
            $table->dropForeign(['rider_id']);
            $table->dropIfExists($this->tableName);
        });
    }
}
