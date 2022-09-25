<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");
            $table->uuid("guid");
            $table->string("vehicle_registration_number");
            $table->date("registered_date");
            $table->string("chassis_number");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
