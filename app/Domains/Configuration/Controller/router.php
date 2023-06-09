<?php declare(strict_types=1);

namespace App\Domains\Configuration\Controller;

use Illuminate\Support\Facades\Route;

Route::get('/configuration/cache', Cache::class)->name('configuration.cache');
