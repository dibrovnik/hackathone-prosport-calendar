<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechEventDisciplines extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_event_disciplines', function($table)
        {
            $table->integer('event_id')->unsigned();
            $table->integer('discipline_id')->unsigned();
            $table->primary(['event_id','discipline_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_event_disciplines');
    }
}
