<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharlieMqttMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('charlie_mqtt_messages'))
        {
            Schema::create('charlie_mqtt_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->string('topic')->nullable();
                $table->longText('message')->nullable();
                $table->boolean('traitement')->default(0);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable();
                $table->dateTime('verification_date')->nullable();
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
        Schema::dropIfExists('charlie_mqtt_messages');
    }
}
