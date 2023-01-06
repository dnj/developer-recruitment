<?php

use App\Enums\DriverStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->foreignId("id")
                ->primary()
                ->constrained("users")
                ->onDelete("CASCADE");
            $table->string("car_model");
            $table->string("car_plate", 8);
            $table->double("latitude")->nullable();
            $table->double("longitude")->nullable();
            $table->enum("status", [
                DriverStatus::WORKING->value,
                DriverStatus::NOT_WORKING->value
            ]);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};
