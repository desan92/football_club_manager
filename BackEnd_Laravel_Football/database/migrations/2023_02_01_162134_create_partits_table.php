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
        Schema::create('partits', function (Blueprint $table) {
            $table->id();
            $table->string('resultat')->nullable();
            $table->unsignedBigInteger('club_local_id');
            $table->unsignedBigInteger('club_visitant_id');
            $table->foreign('club_local_id')->references('id')->on('clubs')->onDelete('cascade'); 
            $table->foreign('club_visitant_id')->references('id')->on('clubs')->onDelete('cascade'); 
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
        Schema::dropIfExists('partits');
    }
};
