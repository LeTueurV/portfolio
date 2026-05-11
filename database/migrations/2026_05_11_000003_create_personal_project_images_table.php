<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_project_id')->constrained('personal_projects')->onDelete('cascade');
            $table->string('image_url');
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_project_images');
    }
};
