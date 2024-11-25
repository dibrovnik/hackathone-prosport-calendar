<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsmans extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sportsmans', function($table)
        {
            $table->string('sub_events', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sportsmans', function($table)
        {
            $table->string('sub_events', 255)->nullable(false)->change();
        });
    }
}
