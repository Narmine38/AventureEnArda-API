<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cette méthode est exécutée lors de la migration.
     * Elle crée la table 'users' avec tous ses champs.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Champs de base pour l'identification et l'authentification
            $table->id(); // Identifiant unique auto-incrémenté pour chaque utilisateur
            $table->string('name'); // Nom de l'utilisateur
            $table->string('email')->unique(); // Email de l'utilisateur (doit être unique)
            $table->timestamp('email_verified_at')->nullable(); // Date et heure de vérification de l'email
            $table->string('password'); // Mot de passe de l'utilisateur
            $table->rememberToken(); // Token pour la fonction "Se souvenir de moi"
            $table->timestamps(); // Crée les champs 'created_at' et 'updated_at'
            // Ajout de la colonne 'deleted_at' pour Soft Deletes
            $table->softDeletes();

            // Informations pratiques supplémentaires
            $table->string('phone_number')->nullable(); // Numéro de téléphone de l'utilisateur
            $table->string('address')->nullable(); // Adresse de l'utilisateur
            $table->string('city')->nullable(); // Ville de l'utilisateur
            $table->string('country')->nullable(); // Pays de l'utilisateur
            $table->string('postal_code')->nullable(); // Code postal de l'utilisateur
        });
    }

    /**
     * Cette méthode est exécutée pour annuler la migration.
     * Elle supprime la table 'users'.
     */
    public function down(): void
    {
        Schema::dropIfExists('users'); // Supprime la table 'users' si elle existe
    }
};
