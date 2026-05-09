<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Lbniy dyal la table categories
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    // Ms7 la table categories
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
