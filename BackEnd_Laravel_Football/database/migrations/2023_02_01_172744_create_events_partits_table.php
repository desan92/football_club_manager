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
        Schema::create('events_partits', function (Blueprint $table) {
            $table->id();
            $table->string('esdeveniment');
            $table->unsignedBigInteger('jugador_id');
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('partit_id');
            $table->foreign('jugador_id')->references('id')->on('jugadors')->onDelete('cascade'); 
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade'); 
            $table->foreign('partit_id')->references('id')->on('partits')->onDelete('cascade'); 
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
        Schema::dropIfExists('events_partits');
    }
};
