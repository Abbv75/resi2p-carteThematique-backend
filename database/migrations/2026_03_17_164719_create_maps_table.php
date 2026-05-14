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
        Schema::create('maps', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('url');
            $table->text('downloadUrl')->nullable();
            $table->text('thumbnail')->nullable();
            
            $table->timestamps();
            
            $table->string('id_thematique');
            $table->foreign('id_thematique')->references('id')->on('thematiques')->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->foreignUuid('id_user')->nullable()->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps');
    }
};
