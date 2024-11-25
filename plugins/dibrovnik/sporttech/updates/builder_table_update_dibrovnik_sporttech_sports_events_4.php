<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsEvents4 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->string('region')->nullable();
            $table->string('city')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->dropColumn('region');
            $table->dropColumn('city');
        });
    }
}
