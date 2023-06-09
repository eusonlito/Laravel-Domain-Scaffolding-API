<?php declare(strict_types=1);

namespace App\Domains\User\Controller;

use Illuminate\Support\Facades\Route;

Route::post('/user/auth', AuthCredentials::class)->name('user.auth.credentials');
Route::post('/user', Create::class)->name('user.create');

Route::post('/user/password-reset', PasswordResetStart::class)->name('user.password-reset.start');
Route::patch('/user/password-reset', PasswordResetFinish::class)->name('user.password-reset.finish');

Route::group(['middleware' => ['user-auth']], static function () {
    Route::post('/user/logout', Logout::class)->name('user.logout');
});

Route::group(['middleware' => ['user-auth-enabled']], static function () {
    Route::patch('/user/auth/refresh', AuthRefresh::class)->name('user.auth.refresh');
    Route::post('/user/confirm', ConfirmStart::class)->name('user.confirm.start');
    Route::patch('/user/confirm', ConfirmFinish::class)->name('user.confirm.finish');
});

Route::group(['middleware' => ['user-auth-enabled-confirmed']], static function () {
    Route::get('/user', Profile::class)->name('user.profile');
    Route::patch('/user', Update::class)->name('user.update');
});
