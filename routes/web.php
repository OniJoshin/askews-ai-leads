<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;


Route::get('/', [LeadController::class, 'showForm']);
Route::post('/submit', [LeadController::class, 'submitForm']);
Route::post('/reply', [LeadController::class, 'handleReply']);
Route::get('/pipeline', fn() => view('pages.pipeline'));
