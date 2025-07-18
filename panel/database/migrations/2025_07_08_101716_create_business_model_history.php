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
        Schema::create('business_model_history', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_study');
            $table->timestamp('start_date')->nullable();
            $table->enum("environment",["production","development"]);
            $table->unsignedBigInteger('author')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_model_history');
    }
};
