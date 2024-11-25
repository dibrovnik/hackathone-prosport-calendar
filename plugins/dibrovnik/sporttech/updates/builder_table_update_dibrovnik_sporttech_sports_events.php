<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechSportsEvents extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->smallInteger('code');
            $table->date('start_date')->nullable()->unsigned(false)->default(null)->comment(null)->change();
            $table->date('end_date')->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_sports_events', function($table)
        {
            $table->dropColumn('code');
            $table->dateTime('start_date')->nullable()->unsigned(false)->default(null)->comment(null)->change();
            $table->dateTime('end_date')->nullable()->unsigned(false)->default(null)->comment(null)->change();
        });
    }
}
