<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHebergementsTable extends Migration
{
    public function up()
    {
        Schema::create('hebergements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 8, 2); // Pour un montant maximum de 999,999.99
            $table->string('photo');
            $table->unsignedBigInteger('lieu_id');
            $table->foreign('lieu_id')->references('id')->on('lieux')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('hebergements');
    }
}
