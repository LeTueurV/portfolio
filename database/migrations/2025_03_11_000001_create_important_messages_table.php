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
        Schema::create('important_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Ex: "Recherche de stage"
            $table->text('message'); // Ex: "Je suis à la recherche d'un stage de 6 mois..."
            $table->string('type')->default('info'); // info, success, warning, urgent
            $table->string('icon')->nullable(); // Icône optionnelle (ex: "briefcase", "search")
            $table->string('link_url')->nullable(); // Lien optionnel
            $table->string('link_text')->nullable(); // Texte du lien
            $table->boolean('is_active')->default(true); // Actif ou non
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->timestamp('start_date')->nullable(); // Date de début d'affichage
            $table->timestamp('end_date')->nullable(); // Date de fin d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('important_messages');
    }
};
