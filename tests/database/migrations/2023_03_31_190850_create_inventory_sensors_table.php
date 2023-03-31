<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventorySensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inventory_sensors'))
        {
            Schema::create('inventory_sensors', function (Blueprint $table) {
                $table->bigInteger('tracker_id');
                $table->integer('inventory_id');
                $table->string('sensor_name', 6);
                $table->integer('rssi');
                
                $table->primary(['tracker_id', 'inventory_id', 'sensor_name']);
                $table->foreign(['tracker_id', 'inventory_id'], 'inventory_sensors_ibfk_1')->references(['tracker_id', 'inventory_id'])->on('inventories')->onDelete('cascade');
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
        Schema::dropIfExists('inventory_sensors');
    }
}
