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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_published')->default(0);
            $table->boolean('coming_soon')->default(0);
            $table->text('description');
            $table->string('steam_id')->nullable();
            $table->integer('popularity')->default(0);
            $table->integer('view_count')->default(0);
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->string('released_date')->nullable(); // if null is it null game will be coming soon
            $table->string('metacritic_url')->nullable();
            $table->integer('metacritic_score')->nullable();
            $table->boolean('allow_update');
            $table->boolean('is_free');
            $table->text('about_the_game');
            $table->text('requirements_min')->nullable();
            $table->text('requirements_rec')->nullable();
            //supported_languages
            //platforms
            //screenshots many to one
            //videos many to one
            //developers many to one
            //publishers many to one
            //package_groups many to one
            //games_sss many to one
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
