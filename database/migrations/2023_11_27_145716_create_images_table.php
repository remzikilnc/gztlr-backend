<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('images', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('model_id')->index();
            $table->string('model_type', 50)->index();
            $table->string('type', 50)->default('external');
            $table->string('url')->nullable();
            $table->string('local')->nullable();
            $table->string('web')->nullable();
            $table->string('source')->default('local')->index();
            $table->integer('order')->unsigned()->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
