<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->constrained('utilisateurs')->onDelete('cascade');
            $table->foreignId('id_categorie')->constrained('categories')->onDelete('cascade');
            $table->string('titre', 255);
            $table->text('description');
            $table->decimal('prix', 10, 2)->nullable();
            $table->enum('statut', ['en_attente', 'publiee', 'rejetee'])->default('en_attente');
            $table->dateTime('date_publication')->useCurrent();
            $table->text('motif_rejet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
