<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\EdifactViewer;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware(['guest', 'blocked'])->group(function () {

    Volt::route('/', 'pages.auth.login')
        ->middleware('throttle:10,1')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->middleware('throttle:5,1')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->middleware('throttle:5,1')
        ->name('password.reset');
});




Route::middleware(['auth', 'blocked', 'is_active'])->group(function () {
    Volt::route('/userIndex/form/{user?}', 'pages.users.edit-user')
        ->name('user.edit');

    // Admin restrictions
    Route::middleware(['role:admin', 'blocked', 'is_active'])->group(function () {

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

        Volt::route('auditoria', 'pages.audits.index')
            ->name('audits.index');
    });

    Route::middleware(['blocked'])->group(function () {
        Volt::route('/services', 'services.index')
            ->name('services.index');

        Volt::route('/services/servicedetail/{service?}', 'services.manage')
            ->name('service.manage');

        Route::get('/edifact-files/{edifactFile}/download', \App\Http\Controllers\EdifactFileDownloadController::class)
            ->name('edifactfiles.download');
        Route::get('/edifact-files/{edifactFile}/view', \App\Http\Controllers\EdifactFileViewController::class)
            ->name('edifactfiles.view');
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
