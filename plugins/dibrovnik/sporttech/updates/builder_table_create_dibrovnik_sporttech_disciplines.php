<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechDisciplines extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_disciplines', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
            $table->integer('sport_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_disciplines');
    }
}
