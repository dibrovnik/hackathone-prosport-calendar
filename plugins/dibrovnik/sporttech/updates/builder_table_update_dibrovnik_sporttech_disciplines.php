<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateDibrovnikSporttechDisciplines extends Migration
{
    public function up()
    {
        Schema::table('dibrovnik_sporttech_disciplines', function($table)
        {
            $table->string('code', 255)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('dibrovnik_sporttech_disciplines', function($table)
        {
            $table->string('code', 255)->nullable(false)->change();
        });
    }
}
