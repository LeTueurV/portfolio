<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_project_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_project_id')->constrained('personal_projects')->onDelete('cascade');
            $table->string('tag', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_project_tags');
    }
};
