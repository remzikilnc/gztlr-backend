<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities');
            $table->string('weather_status');
            $table->string('weather_description')->nullable()->comment('weather_status description');
            $table->string('weather_icon')->default('04d')->comment('weather_status icon');
            $table->timestamp('sunrise');
            $table->timestamp('sunset');
            $table->double('min_temp');
            $table->double('max_temp');
            $table->double('wind_speed');
            $table->double('wind_deg');
            $table->integer('humidity');
            $table->date('applicable_date');
            $table->unique(['city_id', 'applicable_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
