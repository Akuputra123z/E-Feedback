<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::get('/', function () {
    return view('home');
});

Route::get('/survey', [SurveyController::class, 'create']);
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');
