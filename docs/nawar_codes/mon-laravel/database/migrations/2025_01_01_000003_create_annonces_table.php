<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('slug')->unique();
            $table->string('icone')->nullable();
            $table->timestamps();
        });

        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('restrict');
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 12, 2)->nullable();
            $table->string('ville')->nullable();
            $table->string('image')->nullable();
            $table->enum('statut', ['active', 'inactive', 'vendue'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
        Schema::dropIfExists('categories');
    }
};