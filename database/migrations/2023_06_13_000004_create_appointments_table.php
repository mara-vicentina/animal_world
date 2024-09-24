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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('reason');
            $table->decimal('value', 10, 2);
            $table->string('which_vaccines', 255)->nullable();
            $table->boolean('up_to_date_vaccines')->nullable();
            $table->boolean('up_to_date_deworming')->nullable();
            $table->boolean('use_antiparasitics')->nullable();
            $table->string('previous_surgeries', 255)->nullable();
            $table->string('illnesses_chronic_conditions', 255)->nullable();
            $table->string('medication', 255)->nullable();
            $table->string('main_symptoms', 255)->nullable();
            $table->string('change_behavior', 255)->nullable();
            $table->string('temperature', 255)->nullable();
            $table->string('heart_respiratory_rate', 255)->nullable();
            $table->string('mucous_membranes_hydration', 255)->nullable();
            $table->string('weight', 255)->nullable();
            $table->string('pain_scale', 255)->nullable();
            $table->string('skin_coat', 255)->nullable();
            $table->string('eyes_ears', 255)->nullable();
            $table->string('oral_cavity', 255)->nullable();
            $table->string('abdomen_locomotion', 255)->nullable();
            $table->string('reproductive_system', 255)->nullable();
            $table->string('observations_description', 255)->nullable();
            $table->string('exams', 255)->nullable();
            $table->unsignedBigInteger('animal_id');
            $table->foreign('animal_id')->references('id')->on('animals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
