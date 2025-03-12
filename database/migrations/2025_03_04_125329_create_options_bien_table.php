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
        Schema::create('options_bien', function (Blueprint $table) {
            $table->id();
            
            //creation de liaison entre bien et options
            $table->unsignedBigInteger('bien_id');
            $table->foreign('bien_id')->references('id')->on('biens')->onDelete('cascade');

            $table->integer('co2')->nullable();
            $table->integer('consomation_energie')->nullable();

            //creation et assignement pour le type de chauffage
            $table->unsignedBigInteger('type_chauffage_id')->nullable();
            $table->foreign('type_chauffage_id')->references('id')->on('type_chauffages');

            $table->tinyInteger('double_vitrage')->default(0);
            $table->tinyInteger('HVAC')->default(0);
            $table->tinyInteger('solaire')->default(0);
            $table->integer('puissance_solaire')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options_bien');
    }
};
