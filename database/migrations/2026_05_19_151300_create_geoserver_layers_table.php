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
        Schema::create('geoserver_layers', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('type')->comment('type du layer (point, polygon, line, raster…)');
            $table->string('title');
            $table->string('name');
            $table->text('openlayerUrl');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geoserver_layers');
    }
};
