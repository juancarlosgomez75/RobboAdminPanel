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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            //Información del contratista
            $table->string('enterprise');
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();

            //Información de creación
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('creation_notes')->nullable();
            $table->json("creation_list");
            $table->timestamp('tentative_delivery_date')->nullable();

            //Información de entrega
            $table->json("delivery_list")->nullable();

            //Información de estado
            $table->enum('status', ['created', 'partial delivery', 'delivered', 'canceled'])->default('created');

            //Cancelación
            $table->unsignedBigInteger('canceled_by')->nullable();
            $table->foreign('canceled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancel_date')->nullable();

            //Finalización
            $table->unsignedBigInteger('finished_by')->nullable();
            $table->foreign('finished_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('finished_date')->nullable();
            $table->boolean('finished')->default(False);

            //Marcas temporales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
