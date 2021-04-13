<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceTable extends Migration
{
    public $tableName = 'race';

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
            $table->string('title', 100);
            $table->integer('club_id')->unsigned();            
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('address', 255)->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED']);
            $table->softDeletes();

            $table->foreign('club_id')->references('id')->on('club');
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
            $table->dropForeign(['club_id']);
            $table->dropIfExists($this->tableName);
        });
    }
}
