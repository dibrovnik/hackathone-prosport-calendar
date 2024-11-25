<?php namespace Dibrovnik\SportTech\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateDibrovnikSporttechTest extends Migration
{
    public function up()
    {
        Schema::create('dibrovnik_sporttech_test', function($table)
        {
            $table->increments('id')->unsigned();
            $table->text('name');
            $table->text('surname');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('dibrovnik_sporttech_test');
    }
}
