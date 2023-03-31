<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inventories'))
        {
            Schema::create('inventories', function (Blueprint $table) {
                $table->bigInteger('tracker_id');
                $table->integer('inventory_id');
                $table->float('latitude');
                $table->float('longitude');
                $table->integer('remaining_battery');
                $table->dateTime('datetime');
                
                $table->primary(['tracker_id', 'inventory_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
