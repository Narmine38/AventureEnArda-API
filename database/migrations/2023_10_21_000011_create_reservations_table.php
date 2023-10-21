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
            $table->unsignedBigInteger('lieu_id')->nullable();
            $table->unsignedBigInteger('hebergement_id')->nullable();
            $table->unsignedBigInteger('activite_id')->nullable();
            $table->unsignedBigInteger('personnage_id')->nullable();
            $table->date('date_arrivee')->nullable();
            $table->date('date_depart')->nullable();
            $table->integer('nombre_personnes');  // Champ pour le nombre de personnes
            $table->enum('statut', ['pending', 'approved', 'canceled'])->default('pending'); // Champ pour le statut
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lieu_id')->references('id')->on('lieux')->onDelete('cascade');
            $table->foreign('hebergement_id')->references('id')->on('hebergements')->onDelete('cascade');
            $table->foreign('activite_id')->references('id')->on('activites')->onDelete('cascade');
            $table->foreign('personnage_id')->references('id')->on('personnages')->onDelete('cascade');
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
