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
        Schema::create('transferencies_jugadors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_from_id')->nullable();
            $table->unsignedBigInteger('club_to_id')->nullable();
            $table->unsignedBigInteger('jugador_id');
            $table->foreign('club_from_id')->references('id')->on('clubs')->onDelete('cascade'); 
            $table->foreign('club_to_id')->references('id')->on('clubs')->onDelete('cascade'); 
            $table->foreign('jugador_id')->references('id')->on('jugadors')->onDelete('cascade'); 
            $table->date('created_contract');
            $table->date('contract_min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transferencies_jugadors');
    }
};
