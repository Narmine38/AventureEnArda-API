<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('place_id')->nullable();
            $table->unsignedBigInteger('accommodation_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->unsignedBigInteger('character_id')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('starting_date')->nullable();
            $table->integer('number_of_people');  // Champ pour le nombre de personnes
            $table->enum('statut', ['pending', 'approved', 'canceled'])->default('pending'); // Champ pour le statut
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');
            $table->foreign('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activites')->onDelete('cascade');
            $table->foreign('character_id')->references('id')->on('characters')->onDelete('cascade');
        });
    }

    /**
     * Inverse les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
