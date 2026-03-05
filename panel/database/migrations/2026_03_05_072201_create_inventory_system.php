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
        // 1. Clasificación lógica (Organización de productos)
        Schema::create('inv_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Maestro de Productos (Contabilidad de Stock Global)
        Schema::create('inv_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inv_category_id')->constrained('inv_categories');
            $table->string('sku')->unique();
            $table->string('name');

            // 'consumable' (granel) o 'serialized' (único)
            $table->enum('tracking_type', ['consumable','serialized'])
                ->default('consumable');
            $table->boolean('is_assembly')->default(false);

            // Saber si está activo o no
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Bodegas / Ubicaciones (Dónde está la mercancía)
        Schema::create('inv_warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: 'Bodega Principal', 'Taller'
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Stock por Bodega (La ubicación real de cada unidad)
        Schema::create('inv_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inv_warehouse_id')->constrained('inv_warehouses');
            $table->foreignId('inv_product_id')->constrained('inv_products');

            // Saldos específicos en esta ubicación
            $table->unsignedInteger('stock_physical')->default(0);
            $table->unsignedInteger('stock_reserved')->default(0);
            $table->unsignedInteger('stock_damaged')->default(0);

            $table->unique(['inv_warehouse_id', 'inv_product_id'], 'warehouse_product_unique');
            $table->timestamps();

            //índices útiles
            $table->index('inv_product_id');
            $table->index('inv_warehouse_id');
        });

        // 5. Configuración de Eventos (El motor de reglas)
        Schema::create('inv_movement_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: 'Salida por Envío'
            $table->string('code')->unique(); // Ej: 'shipping_exit'

            // Determina qué columna de stock afecta: physical, reserved, damaged, o none
            $table->enum('target_stock', ['physical', 'reserved', 'damaged', 'none'])->default('physical');

            // Operación: sum, subtract, none
            $table->enum('action', ['sum', 'subtract', 'none']);

            $table->timestamps();
        });

        // 6. Trazabilidad Atómica (La "Caja Negra" de Movimientos)
        Schema::create('inv_inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inv_product_id')->constrained('inv_products');
            $table->foreignId('inv_movement_type_id')->constrained('inv_movement_types');

            $table->foreignId('from_warehouse_id')->nullable()->constrained('inv_warehouses');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('inv_warehouses');

            // El Serial: Identidad del equipo si tracking_type es 'serialized'
            $table->string('serial_number')->nullable()->index();

            // Cantidad de la operación
            $table->uuid('operation_uuid')->nullable()->unique();
            $table->unsignedInteger('quantity');

            // BLINDAJE MATEMÁTICO (Snapshot de la bodega específica)
            $table->integer('balance_physical_before');
            $table->integer('balance_physical_after');
            $table->integer('balance_reserved_before');
            $table->integer('balance_reserved_after');
            $table->integer('balance_damaged_before');
            $table->integer('balance_damaged_after');

            // Auditoría
            $table->foreignId('admin_id')->constrained('wa_users');
            $table->nullableMorphs('reference'); // Link polimórfico a órdenes o tablas externas
            $table->text('observations')->nullable();

            $table->timestamps();

            //Índices de crecimiento
            $table->index(['inv_product_id', 'created_at']);
            $table->index(['from_warehouse_id']);
            $table->index(['to_warehouse_id']);
            $table->index('admin_id');
            $table->index(['reference_type','reference_id']);

        });

        // 7. Estructura de Máquinas (Bill of Materials)
        Schema::create('inv_product_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('inv_products')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('inv_products');
            $table->unique(['parent_id','component_id']);
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panel_order_system');
    }
};
