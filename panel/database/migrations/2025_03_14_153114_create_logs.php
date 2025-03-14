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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->string('menu');
            $table->string('section');
            $table->string('action');
            $table->text('details');

            $table->unsignedBigInteger('author')->nullable(); // ✅ Debe coincidir con 'id' de ranks
            $table->foreign('author')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->string('ip_address', 45);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
