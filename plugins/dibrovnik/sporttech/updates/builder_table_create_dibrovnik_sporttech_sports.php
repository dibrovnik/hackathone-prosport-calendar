<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechSports extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_sports', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_sports');
    }
}
