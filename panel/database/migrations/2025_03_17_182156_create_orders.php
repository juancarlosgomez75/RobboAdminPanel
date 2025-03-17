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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            //Información del cliente
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->string('phone');

            //Información de la orden
            

            //Información de revisión
            $table->unsignedBigInteger('checked_by')->nullable();
            $table->foreign('checked_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('check_date')->nullable();
            $table->text('check_notes')->nullable();

            //Información de empaquetado
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('preparation_date')->nullable();
            $table->text('preparation_notes')->nullable();

            //Información de despacho
            $table->unsignedBigInteger('dispatched_by')->nullable();
            $table->foreign('dispatched_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('dispatch_date')->nullable();
            $table->text('dispatch_notes')->nullable();
            
            //Información de envío
            $table->string('tracking');
            $table->string('enterprise');
            $table->decimal('shipping_cost', 10, 2)->nullable();

            //Información de creación
            $table->unsignedBigInteger('creator')->nullable();
            $table->foreign('creator')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('creation_notes')->nullable();
            $table->json('creation_details');

            //Información de estado
            $table->enum('status', ['pending', 'checked', 'prepared', 'dispatched', 'canceled'])->default('pending');

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
        Schema::dropIfExists('orders');
    }
};
