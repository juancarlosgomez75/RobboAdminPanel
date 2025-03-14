<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\StudyController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::get('/RobboAdminPanel/panel/public/livewire/update', $handle);
});

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::controller(LoginController::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth','checkuserstatus'])->controller(StudyController::class)->group(function () {
    Route::get('/estudios', 'index')->name('estudios.index');
    Route::get('/estudios/crear', 'create')->name('estudios.create');
    // Route::middleware('checkrank:2')->get('/estudios/crear', 'create')->name('estudios.create');

    Route::get('/estudio/manager/crear/{idestudio}', 'manager_create')->name('manager.create');
    Route::get('/estudio/manager/{idmanager}', 'manager_viewedit')->name('manager.ver');
    
    Route::get('/estudio/{idestudio}', 'viewedit')->name('estudio.ver');
});

Route::middleware(['auth','checkuserstatus'])->controller(MachineController::class)->group(function(){
    Route::get('/maquinas', 'index')->name('maquinas.index');
    Route::get('/maquinas/crear', 'create')->name('maquinas.create');
});

Route::middleware(['auth','checkuserstatus'])->controller(ModelController::class)->group(function(){
    Route::get('/modelo/{idmodelo}', 'viewedit')->name('modelos.viewedit');
});

Route::middleware(['auth','checkuserstatus'])->controller(AdminController::class)->group(function(){
    Route::middleware('checkrank:4')->get('/cuentas', 'accounts')->name('admin.accounts');
    Route::get('/cuenta/{idcuenta}', 'account')->name('admin.account.view');
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
