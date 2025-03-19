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
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();

            //Información del cliente
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->string('phone');

            //Información del estudio, si la hay
            $table->unsignedBigInteger('study_id')->nullable();

            //Información de creación
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('creation_notes')->nullable();
            $table->json("creation_list");

            //Información de empaquetado
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->json("preparation_list")->nullable();
            $table->timestamp('preparation_date')->nullable();
            $table->text('preparation_notes')->nullable();
            
            //Información de envío
            $table->unsignedBigInteger('sended_by')->nullable();
            $table->foreign('sended_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('tracking')->nullable();
            $table->string('enterprise')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();

            //Información de estado
            $table->enum('status', ['created', 'prepared', 'waiting', 'sended', 'canceled'])->default('created');

            //Cancelación
            $table->unsignedBigInteger('canceled_by')->nullable();
            $table->foreign('canceled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancel_date')->nullable();

            //Marcas temporales
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
