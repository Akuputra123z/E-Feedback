<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurveyController;

Route::get('/', function () {
    return view('home');
});

Route::get('/survey', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])
    ->middleware('throttle:10,1')->name('survey.store');

Route::get('/survey/results', [App\Http\Controllers\SurveyController::class, 'results'])->name('survey.results');