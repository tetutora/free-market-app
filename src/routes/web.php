<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
});