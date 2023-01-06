<?php

use App\Enums\TravelStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->id();
            $table->foreignId("passenger_id")
                ->constrained("users");
            $table->foreignId("driver_id")
                ->nullable()
                ->constrained("drivers");
            $table->timestamps();
            $table->enum("status", [
                TravelStatus::SEARCHING_FOR_DRIVER->value,
                TravelStatus::RUNNING->value,
                TravelStatus::DONE->value,
                TravelStatus::CANCELLED->value,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travels');
    }
};
