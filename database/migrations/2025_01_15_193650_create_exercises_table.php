<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de l'exercice
            $table->text('description')->nullable(); // Description succincte
            $table->unsignedBigInteger('module_id'); // Module associé
            $table->enum('level', ['facile', 'intermédiaire', 'avancé']); // Niveau de difficulté
            $table->text('instructions'); // Enoncé détaillé
            $table->string('path')->nullable(); // Enoncé détaillé
            $table->string('image_path')->nullable(); // Image associée au TP
            $table->timestamps();

            // Relation avec la table modules
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
