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
        Schema::create('machine_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');

            $table->string('description');
            $table->text('details');

            $table->unsignedBigInteger('author');
            $table->foreign('author')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_history');
    }
};
