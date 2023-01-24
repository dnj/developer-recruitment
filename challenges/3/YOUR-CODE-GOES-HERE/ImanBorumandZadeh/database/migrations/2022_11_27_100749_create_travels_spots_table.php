<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("travels_spots", function (Blueprint $table) {
            $table->id();
            $table->foreignId("travel_id")
                ->constrained("travels")
                ->onDelete("CASCADE");
            $table->unsignedTinyInteger("position");
            $table->double("latitude");
            $table->double("longitude");
            $table->timestamp("arrived_at")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travels_spots');
    }
};
