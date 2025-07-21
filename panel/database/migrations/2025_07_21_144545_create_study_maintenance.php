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
        Schema::create('study_maintenance', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('study_id');

            $table->text('description');

            $table->unsignedBigInteger('author');
            $table->foreign('author')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_maintenance');
    }
};
