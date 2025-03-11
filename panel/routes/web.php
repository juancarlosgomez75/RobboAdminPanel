<?php

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
    Route::get('/estudio/manager/{idmanager}', 'manager_viewedit')->name('manager.ver');
    Route::get('/estudio/{idestudio}', 'viewedit')->name('estudio.ver');
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
