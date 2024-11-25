<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechSportDiscipline extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_sport_discipline', function($table)
        {
            $table->integer('sport_id')->unsigned();
            $table->integer('discipline_id')->unsigned();
            $table->primary(['sport_id','discipline_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_sport_discipline');
    }
}
