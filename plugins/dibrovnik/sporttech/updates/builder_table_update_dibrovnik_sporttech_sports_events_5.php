<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsEvents5 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->string('code', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->string('code', 255)->nullable(false)->change();
        });
    }
}
