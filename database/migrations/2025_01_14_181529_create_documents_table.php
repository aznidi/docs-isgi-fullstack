<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('codeDoc')->unique();
            $table->string('nomDoc');
            $table->string('libelleDoc')->nullable();
            $table->text('descriptionDoc')->nullable();
            $table->enum('type', ['pdf', 'video', 'tp', 'efm', 'control', 'cours']); // Types possibles
            $table->string('path'); // Chemin du fichier ou lien vidéo
            $table->unsignedBigInteger('module_id');
            $table->integer('nbTelechargements')->default(0);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
}
