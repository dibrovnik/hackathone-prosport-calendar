<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechEventDisciplines extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_event_disciplines', function($table)
        {
            $table->dropPrimary(['event_id','discipline_id']);
            $table->renameColumn('event_id', 'sport_event_id');
            $table->primary(['sport_event_id','discipline_id']);
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_event_disciplines', function($table)
        {
            $table->dropPrimary(['sport_event_id','discipline_id']);
            $table->renameColumn('sport_event_id', 'event_id');
            $table->primary(['event_id','discipline_id']);
        });
    }
}
