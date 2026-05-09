<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter colonnes manquantes à users
        Schema::table('users', function (Blueprint $table) {
            $table->string('prenom', 100)->nullable()->after('name');
            $table->enum('role', ['visiteur', 'membre', 'admin'])->default('membre')->after('prenom');
            $table->enum('statut', ['actif', 'suspendu', 'banni'])->default('actif')->after('role');
        });

        // Modifier table annonces
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
        Schema::table('annonces', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'publiee', 'rejetee'])->default('en_attente')->after('prix');
            $table->text('motif_rejet')->nullable()->after('statut');
            $table->dateTime('date_publication')->nullable()->after('motif_rejet');
        });

        // Table photos multiples
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annonce_id')->constrained('annonces')->onDelete('cascade');
            $table->string('url', 255);
            $table->string('nom_fichier', 255);
            $table->integer('ordre')->default(1);
            $table->timestamps();
        });

        // Table messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_expediteur')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_destinataire')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_annonce')->constrained('annonces')->onDelete('cascade');
            $table->string('objet', 255)->nullable();
            $table->text('contenu');
            $table->dateTime('date_envoi')->useCurrent();
            $table->tinyInteger('lu')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('photos');
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropColumn(['motif_rejet', 'date_publication']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prenom', 'role', 'statut']);
        });
    }
};