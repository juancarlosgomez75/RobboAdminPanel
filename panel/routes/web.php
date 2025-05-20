<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StudyController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::get(env('LIVEWIRE_CUSTOM_URL', '/RobboAdminPanel/panel/public/livewire/update'), $handle);
    
});

Route::get('/', function () {
    return redirect(route("login"));
})->name('home');

Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth','checkuserstatus'])->controller(StudyController::class)->group(function () {
    Route::get('/panel/estudios', 'index')->name('estudios.index');
    Route::middleware('checkrank:4')->get('/panel/estudios/crear', 'create')->name('estudios.create');

    Route::middleware('checkrank:4')->get('/panel/estudio/manager/crear/{idestudio}', 'manager_create')->name('manager.create');
    Route::middleware('checkrank:4')->get('/panel/estudio/manager/{idmanager}', 'manager_viewedit')->name('manager.ver');
    
    Route::get('/panel/estudio/{idestudio}', 'viewedit')->name('estudio.ver');

    Route::middleware('checkrank:4')->get('/panel/reportes', 'report')->name('reportes');
    Route::get('/panel/prueba', 'prueba')->name('prueba');
});

Route::middleware(['auth','checkuserstatus'])->controller(MachineController::class)->group(function(){
    Route::get('/panel/maquinas', 'index')->name('maquinas.index');
    Route::middleware('checkrank:5')->get('/panel/maquinas/crear', 'create')->name('maquinas.create');
    Route::get('/panel/maquina/{idmaquina}', 'view')->name('maquinas.view');
});

Route::middleware(['auth','checkuserstatus'])->controller(ModelController::class)->group(function(){
    Route::get('/panel/modelos', 'view')->name('modelos.view');
    Route::middleware('checkrank:4')->get('/panel/modelos/crear/{idestudio}', 'create')->name('modelos.create');
    Route::get('/panel/modelo/{idmodelo}', 'viewedit')->name('modelo.viewedit');
});

Route::middleware(['auth','checkuserstatus'])->middleware('checkrank:4')->controller(AdminController::class)->group(function(){
    Route::get('/panel/cuentas', 'accounts')->name('admin.accounts');
    Route::get('/panel/cuenta/{idcuenta}', 'account')->name('admin.account.view');
    Route::get('/panel/logs', 'logs')->name('admin.logs');
    Route::get('/panel/log/{idlog}', 'log')->name('admin.log');
});

Route::middleware(['auth','checkuserstatus'])->controller(PanelController::class)->group(function(){
    Route::get('/panel/perfil', 'profile_view')->name('panel.perfil.view');
    Route::get('/panel', 'index')->name('panel.index');
    Route::get('/panel/consulta', 'consulta')->name('panel.consulta');
    Route::get('/panel/dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth','checkuserstatus'])->middleware('checkrank:2')->controller(InventoryController::class)->group(function(){
    //Productos
    Route::get('/panel/productos', 'index')->name('inventario.index');
    Route::middleware('checkrank:4')->get('/panel/producto/movimiento/{idinventario}', 'movement')->name('inventario.movimiento');
    Route::get('/panel/producto/{idproducto}', 'viewedit')->name('inventario.viewedit');
    Route::get('/panel/mensajeria', 'couriers')->name('inventario.couriers');

    //órdenes
    Route::get('/panel/ordenes', 'order_list')->name('ordenes');
    Route::middleware('checkrank:4')->get('/panel/ordenes/crear', 'order_create')->name('ordenes.create');
    Route::get('/panel/orden/{idorden}', 'order_view')->name('orden.ver');

    //Pedidos
    Route::get('/panel/pedidos', 'request_list')->name('pedidos');
    Route::middleware('checkrank:4')->get('/panel/pedido', 'request')->name('pedido');
});

Route::middleware(['auth','checkuserstatus'])->middleware('checkrank:2')->controller(PdfController::class)->group(function(){
    //Productos
    Route::get('/generar-pdf', 'generatePdf');
    Route::post('/reporte-pdf', 'generateReport')->name('reporte.pdf');
    Route::get('/reporte-get', 'getReport')->name('reporte.get');
});


// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Route::get('settings/profile', Profile::class)->name('settings.profile');
//     Route::get('settings/password', Password::class)->name('settings.password');
//     Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
// });

// require __DIR__.'/auth.php';
