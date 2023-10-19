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
        Schema::create('personnages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('histoire');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('lieu_id');
            $table->foreign('lieu_id')->references('id')->on('lieux')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnages');
    }
};
