<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsTable extends Migration
{
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exercise_id'); // Exercice associé
            $table->enum('solution_type', ['texte', 'pdf', 'vidéo', 'fichier']); // Type de solution
            $table->text('content')->nullable(); // Contenu de la solution
            $table->string('path')->nullable(); // Contenu de la solution
            $table->string('file_path')->nullable(); // Chemin du fichier si applicable
            $table->timestamps();

            // Relation avec la table exercises
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('solutions');
    }
}
