<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cette méthode est exécutée lors de la migration.
     * Elle crée la table 'password_reset_tokens' pour gérer les tokens de réinitialisation de mot de passe.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->bigIncrements('id'); // Ceci créera une clé primaire auto-incrémentée nommée 'id'
            $table->string('email')->unique();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Cette méthode est exécutée pour annuler la migration.
     * Elle supprime la table 'password_reset_tokens'.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens'); // Supprime la table 'password_reset_tokens' si elle existe
    }
};
