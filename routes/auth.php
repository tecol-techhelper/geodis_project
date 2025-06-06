<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\EdifactViewer;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {

    Volt::route('/', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});




Route::middleware(['auth'])->group(function () {
    Volt::route('/userIndex/form/{user?}', 'pages.users.edit-user')
        ->name('user.edit');

    // Admin restrictions
    Route::middleware(['role:admin'])->group(function () {

        Volt::route('/userIndex/create', 'pages.users.create-user')
            ->name('user.create');

        // Volt::route('/userIndex/form/{user?}', 'pages.users.edit-user')
        //     ->name('user.edit');

        Volt::route('usersIndex', 'pages.users.index')
            ->name('user.index');

        Volt::route('edifactViewer', 'services.edifact-file-manager.edifact-file-viewer')
            ->name('edifact.viewer');

        Volt::route('filesIndex', 'services.edifact-file-manager.files-index')
            ->name('edifactfiles.index');

        Volt::route('uploadFile', 'services.upload-file-modal')->name('upload.file');

        Volt::route('file_management', 'services.uploaded-file')->name('uploaded.file');
    });

    Route::middleware(['role:admin,coord'])->group(function () {
        Volt::route('/servicesIndex', 'services.edit-new-service')
            ->name('service.index');
    });
});




// Route::middleware('auth')->group(function () {
//     Volt::route('verify-email', 'pages.auth.verify-email')
//         ->name('verification.notice');

//     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');

//     Volt::route('confirm-password', 'pages.auth.confirm-password')
//         ->name('password.confirm');
// });
