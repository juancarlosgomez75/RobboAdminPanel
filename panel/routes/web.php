<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\StudyController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::controller(StudyController::class)->group(function(){
    Route::get('/estudios', 'index')->name('estudios.index');
    Route::get('/estudios/crear', 'create')->name('estudios.create');

    Route::get('/estudio/manager/crear/{idestudio}', 'manager_create')->name('manager.create');
    Route::get('/estudio/manager/{idmanager}', 'manager_viewedit')->name('manager.ver');
    
    Route::get('/estudio/{idestudio}', 'viewedit')->name('estudio.ver');
});

Route::controller(MachineController::class)->group(function(){
    Route::get('/maquinas', 'index')->name('maquinas.index');
    Route::get('/maquinas/crear', 'create')->name('maquinas.create');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
