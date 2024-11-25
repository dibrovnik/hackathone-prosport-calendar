<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsEvents2 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->bigInteger('code')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->smallInteger('code')->nullable(false)->unsigned(false)->default(null)->comment(null)->change();
        });
    }
}
