<?php

use App\Http\Controllers\FaceRecognitionController;

Route::post('/register-face', [FaceRecognitionController::class, 'registerFace']);
Route::post('/login-face', [FaceRecognitionController::class, 'loginFace']);


Route::post('/login-face', [FaceRecognitionController::class, 'loginFace']);
