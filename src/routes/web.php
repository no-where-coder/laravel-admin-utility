<?php

use Illuminate\Support\Facades\Route;
use Nowhere\AdminUtility\Http\Controllers\LoginController;

Route::get('admin-utility   /hello', function () {
    return view('admin-utility::hello');
});

Route::get('admin-utility/login', [LoginController::class, 'magicLogin']);