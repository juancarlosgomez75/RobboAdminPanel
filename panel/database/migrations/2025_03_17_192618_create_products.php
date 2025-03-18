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
        //Primero la tabla de categorías
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');

            //Timestamp
            $table->timestamps();
        });

        //Luego la tabla de productos
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');

            //Información de categoría del producto
            $table->unsignedBigInteger('category')->nullable();
            $table->foreign('category')->references('id')->on('product_categories')->onDelete('set null')->onUpdate('cascade');

            //Información de estado
            $table->boolean("available")->default(true);

            //Información de referencia
            $table->string('ref')->nullable();

            //Timestamp
            $table->timestamps();
        });

        //Luego la tabla de inventarios
        Schema::create('product_inventory', function (Blueprint $table) {
            $table->id();

            //Información de categoría del producto
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null')->onUpdate('cascade');

            //Variables de stock
            $table->integer('stock_available')->default(0);
            $table->integer('stock_min')->default(0);
            $table->integer('stock_rec')->default(0);

            //Timestamp
            $table->timestamps();
        });

        //Luego la tabla de movimientos
        Schema::create('product_inventory_movements', function (Blueprint $table) {
            $table->id();

            //Información de la relación con el inventario
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->foreign('inventory_id')->references('id')->on('product_inventory')->onDelete('set null')->onUpdate('cascade');

            //Ahora el tipo
            $table->enum('payment_method', ['income', 'expense'])->nullable();

            //Ahora información del movimiento
            $table->text('reason');
            $table->integer('amount')->default(0);
            $table->integer('stock_before')->default(0);
            $table->integer('stock_after')->default(0);

            //Ahora información del autor
            $table->unsignedBigInteger('author')->nullable(); // ✅ Debe coincidir con 'id' de ranks
            $table->foreign('author')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');

            //Ahora información de la orden, si existe
            $table->unsignedBigInteger('order_id')->nullable(); //Queda pendiente de amarrarse
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null')->onUpdate('cascade');

            //Timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_movements');
        Schema::dropIfExists('product_inventory');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
