<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechSavedFilters extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_saved_filters', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('query');
            $table->smallInteger('name');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_saved_filters');
    }
}
