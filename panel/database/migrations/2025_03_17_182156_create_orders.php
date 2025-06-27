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
            $table->enum('type', ['shipping', 'collection'])->default('shipping');

            //Información de empaquetado
            $table->unsignedBigInteger('prepared_by')->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->json("preparation_list")->nullable();
            $table->timestamp('preparation_date')->nullable();
            $table->text('preparation_notes')->nullable();

            //Información de envío
            $table->unsignedBigInteger('enlisted_by')->nullable();
            $table->foreign('enlisted_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('enlist_date')->nullable();
            $table->string('tracking')->nullable();
            $table->unsignedBigInteger('enterprise')->nullable();
            $table->foreign('enterprise')->references('id')->on('couriers')->onDelete('set null')->onUpdate('cascade');
            $table->decimal('shipping_cost', 10, 2)->nullable();

            //Información de envío
            $table->unsignedBigInteger('sended_by')->nullable();
            $table->foreign('sended_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('send_date')->nullable();

            //Información de llegada
            $table->unsignedBigInteger('received_by')->nullable();
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('received_date')->nullable();
            $table->text('received_notes')->nullable();

            //Información de estado
            $table->enum('status', ['created', 'prepared', 'waiting', 'sended', 'canceled','collected'])->default('created');

            //Cancelación
            $table->unsignedBigInteger('canceled_by')->nullable();
            $table->foreign('canceled_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancel_date')->nullable();

            //Finalización
            $table->unsignedBigInteger('finished_by')->nullable();
            $table->foreign('finished_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('finished_date')->nullable();
            $table->string('internal_code')->nullable();
            $table->boolean('finished')->default(False);

            //El id de la razón
            $table->unsignedBigInteger('collection_reason')->nullable();
            $table->foreign('collection_reason')->references('id')->on('collection_reasons')->onDelete('set null')->onUpdate('cascade');

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
