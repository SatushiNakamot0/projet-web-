<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Lbniy dyal la table messages
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_expediteur')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('id_destinataire')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('id_annonce')->constrained('annonces')->onDelete('cascade');
            $table->string('objet', 255);
            $table->text('contenu');
            $table->dateTime('date_envoi')->useCurrent();
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    // Ms7 la table messages
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
