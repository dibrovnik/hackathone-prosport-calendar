<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechSportsmans extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_sportsmans', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('sub_events');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_sportsmans');
    }
}
