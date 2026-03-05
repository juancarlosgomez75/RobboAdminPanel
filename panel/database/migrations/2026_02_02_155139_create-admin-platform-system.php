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
        Schema::create('wa_users_permissions', function (Blueprint $table) {
            $table->id();

            // Información del permiso
            $table->string('module')->index(); // Ej: 'users', 'posts', 'settings'
            $table->string('name')->unique();      // Ej: 'users_view', 'posts_create'
            $table->string('display_name')->nullable(); // Ej: 'Visualización'
            $table->text('description')->nullable();

            //Estados
            $table->boolean('active')->default(true);

            //Trazabilidad
            $table->timestamps();
        });

        Schema::create('wa_users_roles', function (Blueprint $table) {
            $table->id();

            //Información general del rol
            $table->string('name');
            $table->text('description');

            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);

            //Información de trazabilidad
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('wa_users_roles_permissions', function (Blueprint $table) {
            //Información de permiso
            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('wa_users_permissions')->onDelete('cascade')->onUpdate('cascade');

            //Información del rol
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('wa_users_roles')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['role_id', 'permission_id']);

            //Información de trazabilidad
            $table->timestamps();
        });

        Schema::create('wa_users', function (Blueprint $table) {
            $table->id();

            //Información general de la cuenta
            $table->string('name');
            $table->string('email')->unique();

            //Estados
            $table->boolean('active')->default(true);
            $table->boolean('deleted')->default(false);

            //Información de acceso
            $table->unsignedBigInteger('role_id')->nullable(); // Almacenará la id del rol
            $table->foreign('role_id')->references('id')->on('wa_users_roles')->onDelete('set null')->onUpdate('cascade');

            $table->bigInteger('creator_id')->nullable(); //Indicará la id del admin que lo creó
            $table->foreign('creator_id')
                    ->references('id')
                    ->on('wa_users') // Auto-referencia
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('wa_logs', function (Blueprint $table) {
            $table->id();

            $table->string('menu');
            $table->string('section');
            $table->string('action');
            $table->text('details');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('wa_users')->onDelete('set null')->onUpdate('cascade');

            $table->string('ip_address', 45);
            $table->boolean(("result"))->default(true);
            $table->timestamps();


        });

        Schema::create('wa_access_codes', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('wa_users')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('study_id');

            $table->timestamp('expires_at')->nullable();
            $table->boolean('used')->default(false);
            $table->boolean('revoked')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_access_codes');
        Schema::dropIfExists('wa_logs');
        Schema::dropIfExists('wa_users');
        Schema::dropIfExists('wa_users_roles_permissions');
        Schema::dropIfExists('wa_users_roles');
        Schema::dropIfExists('wa_users_permissions');
    }
};
