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
            //link id to bien id
            $table->foreign('id')->references('id')->on('biens')->onDelete('cascade');

            $table->integer('co2')->nullable();
            $table->integer('consomation_energie')->nullable();
            $table->string('type_chauffage', 40)->nullable();
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
