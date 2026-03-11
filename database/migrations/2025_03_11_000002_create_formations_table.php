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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ex: "BTS SIO SLAM", "Licence Pro", "BAC"
            $table->string('school'); // Ex: "Lycée X", "Université Y"
            $table->string('location')->nullable(); // Ex: "Paris, France"
            $table->string('degree_type')->nullable(); // Ex: "BTS", "Licence", "Master", "BAC"
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null si en cours
            $table->boolean('is_current')->default(false); // En cours ?
            $table->text('description')->nullable(); // Description du parcours
            $table->string('logo_url')->nullable(); // Logo de l'école
            $table->string('diploma_url')->nullable(); // URL vers le diplôme (PDF)
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
