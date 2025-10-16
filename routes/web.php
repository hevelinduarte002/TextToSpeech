<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpeechController;

Route::get('/', [SpeechController::class, 'index']);
Route::post('/speak',[SpeechController::class,'speak'])->name('speak');
