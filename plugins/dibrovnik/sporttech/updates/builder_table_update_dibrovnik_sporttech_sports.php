<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSports extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sports', function($table)
        {
            $table->string('code', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sports', function($table)
        {
            $table->string('code', 255)->nullable(false)->change();
        });
    }
}
