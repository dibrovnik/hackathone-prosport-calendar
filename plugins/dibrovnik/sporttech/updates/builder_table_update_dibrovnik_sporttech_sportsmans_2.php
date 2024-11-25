<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsmans2 extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sportsmans', function($table)
        {
            $table->string('phone')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sportsmans', function($table)
        {
            $table->dropColumn('phone');
        });
    }
}
