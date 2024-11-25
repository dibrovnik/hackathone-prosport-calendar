<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechSportsEvents extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->string('age_group')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->integer('participants_count')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_sports_events');
    }
}
