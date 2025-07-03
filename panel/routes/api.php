<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post('/dashboard', [ApiController::class, 'dashboard'])->name('api.dashboard');
