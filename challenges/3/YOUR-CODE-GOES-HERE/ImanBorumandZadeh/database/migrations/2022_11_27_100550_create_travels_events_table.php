<?php

use App\Enums\TravelEventType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create("travels_events", function (Blueprint $table) {
            $table->id();
            $table->foreignId("travel_id")
                ->constrained("travels")
                ->onDelete("CASCADE");
            $table->enum("type", [
                TravelEventType::ACCEPT_BY_DRIVER->value,
                TravelEventType::PASSENGER_ONBOARD->value,
                TravelEventType::DONE->value,
                TravelEventType::CANCEL->value,
            ]);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travels_events');
    }
};
