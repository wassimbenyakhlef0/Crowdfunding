<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->decimal('objectif', 10, 2);
            $table->decimal('montant_collecte', 10, 2)->default(0);
            $table->date('date_fin');
            $table->string('categorie'); // ex: "films", "musique", "art", "startup"
            $table->string('image_principale')->nullable(); // chemin de l'image
            $table->string('video_url')->nullable(); // lien YouTube/Viméo
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // le porteur du projet
            $table->enum('statut', ['brouillon', 'publie', 'termine'])->default('brouillon');
            $table->string('slug')->unique(); // pour les URLs SEO-friendly
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projets');
    }
}
