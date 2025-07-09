<?php

use Illuminate\Support\Facades\Route;

Route::get('admin-utility/hello', function () {
    return view('admin-utility::hello');
});
