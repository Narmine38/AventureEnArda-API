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
            // Champs pour gérer la réinitialisation de mot de passe
            $table->id(); // Ceci créera une clé primaire auto-incrémentée nommée 'id'
            $table->string('email')->primary(); // L'email de l'utilisateur servant de clé primaire
            $table->string('token'); // Le token généré pour la réinitialisation de mot de passe
            $table->timestamp('created_at')->nullable(); // La date et l'heure de création du token pour éventuellement vérifier sa validité
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
