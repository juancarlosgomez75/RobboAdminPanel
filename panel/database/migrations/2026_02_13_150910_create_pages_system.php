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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');

            // Clasificación
            $table->enum('type', ['freemium', 'private', 'content', 'other'])->default('freemium');
            $table->text('internal_notes')->nullable();

            $table->boolean('active')->default(true);

            //Borrado
            $table->boolean('deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
